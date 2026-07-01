<?php

namespace Tests\Unit;

use App\Services\NeuralSuggestionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NeuralSuggestionServiceTest extends TestCase
{
    use RefreshDatabase;

    private NeuralSuggestionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new NeuralSuggestionService();
    }

    /** @test */
    public function it_returns_correct_structure(): void
    {
        $result = $this->service->suggest('أريد تصنيف صور');

        $this->assertArrayHasKey('domain', $result);
        $this->assertArrayHasKey('analysis', $result);
        $this->assertArrayHasKey('architectures', $result);
    }

    /** @test */
    public function it_detects_vision_domain(): void
    {
        $result = $this->service->suggest('أريد بناء نموذج لتصنيف الصور الطبية');
        $this->assertEquals('vision', $result['domain']);
    }

    /** @test */
    public function it_detects_nlp_domain(): void
    {
        $result = $this->service->suggest('أريد ترجمة النصوص العربية');
        $this->assertEquals('nlp', $result['domain']);
    }

    /** @test */
    public function it_detects_time_series_domain(): void
    {
        $result = $this->service->suggest('التنبؤ بأسعار الأسهم');
        $this->assertEquals('time_series', $result['domain']);
    }

    /** @test */
    public function it_returns_fallback_for_unknown_problem(): void
    {
        $result = $this->service->suggest('مشكلة لا تحتوي على كلمات مفتاحية معروفة xyz123');
        $this->assertEquals('general', $result['domain']);
        $this->assertNotEmpty($result['architectures']);
    }

    /** @test */
    public function it_returns_max_5_architectures(): void
    {
        $result = $this->service->suggest('تصنيف الصور والنصوص والأسهم والصوت والرسوم');
        $this->assertLessThanOrEqual(5, count($result['architectures']));
    }
}
