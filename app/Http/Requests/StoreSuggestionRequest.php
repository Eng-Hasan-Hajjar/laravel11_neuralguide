<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreSuggestionRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array { return ['problem_text'=>['required','string','min:10','max:5000']]; }
    public function attributes(): array { return ['problem_text'=>'وصف المشكلة أو الفكرة']; }
}
