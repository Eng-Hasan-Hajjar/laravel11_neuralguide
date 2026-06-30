<?php

namespace App\Services;

use App\Models\Architecture;

class PythonCodeGeneratorService
{
    public function generate(Architecture $arch, array $params): string
    {
        $framework = $params['framework'] ?? 'pytorch';
        $epochs    = $params['epochs']    ?? 10;
        $bs        = $params['batch_size'] ?? 32;
        $lr        = $params['learning_rate'] ?? 0.001;
        $opt       = $params['optimizer']  ?? 'adam';
        $loss      = $params['loss_function'] ?? 'cross_entropy';
        $name      = $arch->name;

        if ($framework === 'pytorch') {
            return $this->generatePytorch($name, $arch, $epochs, $bs, $lr, $opt, $loss);
        }

        return $this->generateTensorflow($name, $arch, $epochs, $bs, $lr, $opt, $loss);
    }

    private function generatePytorch(string $name, Architecture $arch, $epochs, $bs, $lr, $opt, $loss): string
    {
        $optCode = match ($opt) {
            'sgd'    => "optim.SGD(model.parameters(), lr={$lr}, momentum=0.9)",
            'adamw'  => "optim.AdamW(model.parameters(), lr={$lr}, weight_decay=1e-4)",
            'rmsprop'=> "optim.RMSprop(model.parameters(), lr={$lr})",
            default  => "optim.Adam(model.parameters(), lr={$lr})",
        };

        $lossCode = match ($loss) {
            'mse'           => 'nn.MSELoss()',
            'bce'           => 'nn.BCELoss()',
            'bce_logits'    => 'nn.BCEWithLogitsLoss()',
            'nll'           => 'nn.NLLLoss()',
            default         => 'nn.CrossEntropyLoss()',
        };

        $modelExample = $arch->pytorch_example ?? $this->defaultPytorchModel($name);

        return <<<PYTHON
# =============================================================
#  NeuralGuide — تجربة تدريب: {$name}
#  إطار العمل: PyTorch
#  تاريخ التوليد: {$this->date()}
# =============================================================

import torch
import torch.nn as nn
import torch.optim as optim
from torch.utils.data import DataLoader, TensorDataset
import numpy as np

# ─── إعدادات التجربة ─────────────────────────────────────────
EPOCHS        = {$epochs}
BATCH_SIZE    = {$bs}
LEARNING_RATE = {$lr}
DEVICE        = torch.device('cuda' if torch.cuda.is_available() else 'cpu')

print(f"📱 الجهاز المستخدم: {'{DEVICE}'}")

# ─── تعريف النموذج: {$name} ───────────────────────────────────
{$modelExample}

# ─── تهيئة النموذج ────────────────────────────────────────────
model = Model().to(DEVICE)
criterion = {$lossCode}
optimizer = {$optCode}

# ─── تحميل البيانات (عدّل هنا مسار بياناتك) ──────────────────
def load_data():
    # مثال: استبدل هذا بتحميل بياناتك الفعلية
    X = torch.randn(1000, 10)
    y = torch.randint(0, 2, (1000,))
    dataset = TensorDataset(X, y)
    train_size = int(0.8 * len(dataset))
    val_size   = len(dataset) - train_size
    train_ds, val_ds = torch.utils.data.random_split(dataset, [train_size, val_size])
    return (
        DataLoader(train_ds, batch_size=BATCH_SIZE, shuffle=True),
        DataLoader(val_ds,   batch_size=BATCH_SIZE),
    )

train_loader, val_loader = load_data()

# ─── حلقة التدريب ────────────────────────────────────────────
history = {'train_loss': [], 'val_loss': [], 'val_acc': []}

for epoch in range(1, EPOCHS + 1):
    # تدريب
    model.train()
    running_loss = 0.0
    for X_batch, y_batch in train_loader:
        X_batch, y_batch = X_batch.to(DEVICE), y_batch.to(DEVICE)
        optimizer.zero_grad()
        outputs = model(X_batch)
        loss = criterion(outputs, y_batch)
        loss.backward()
        optimizer.step()
        running_loss += loss.item() * X_batch.size(0)
    train_loss = running_loss / len(train_loader.dataset)

    # تقييم
    model.eval()
    val_loss, correct, total = 0.0, 0, 0
    with torch.no_grad():
        for X_batch, y_batch in val_loader:
            X_batch, y_batch = X_batch.to(DEVICE), y_batch.to(DEVICE)
            outputs = model(X_batch)
            loss = criterion(outputs, y_batch)
            val_loss += loss.item() * X_batch.size(0)
            _, predicted = outputs.max(1)
            total   += y_batch.size(0)
            correct += predicted.eq(y_batch).sum().item()

    val_loss /= len(val_loader.dataset)
    val_acc   = 100.0 * correct / total if total else 0

    history['train_loss'].append(train_loss)
    history['val_loss'].append(val_loss)
    history['val_acc'].append(val_acc)

    print(f"Epoch [{epoch:03d}/{EPOCHS}]  "
          f"Train Loss: {'{train_loss:.4f}'}  "
          f"Val Loss: {'{val_loss:.4f}'}  "
          f"Val Acc: {'{val_acc:.2f}'}%")

# ─── حفظ النموذج ─────────────────────────────────────────────
torch.save(model.state_dict(), "model_{$this->slug($name)}.pth")
print("✅ تم حفظ النموذج في model_{$this->slug($name)}.pth")
PYTHON;
    }

    private function generateTensorflow(string $name, Architecture $arch, $epochs, $bs, $lr, $opt, $loss): string
    {
        $modelExample = $arch->tensorflow_example ?? $this->defaultKerasModel($name);

        $optCode = match ($opt) {
            'sgd'    => "tf.keras.optimizers.SGD(learning_rate={$lr}, momentum=0.9)",
            'adamw'  => "tf.keras.optimizers.AdamW(learning_rate={$lr})",
            'rmsprop'=> "tf.keras.optimizers.RMSprop(learning_rate={$lr})",
            default  => "tf.keras.optimizers.Adam(learning_rate={$lr})",
        };

        $lossCode = match ($loss) {
            'mse'       => "'mean_squared_error'",
            'bce'       => "'binary_crossentropy'",
            'nll'       => "'sparse_categorical_crossentropy'",
            default     => "'sparse_categorical_crossentropy'",
        };

        return <<<PYTHON
# =============================================================
#  NeuralGuide — تجربة تدريب: {$name}
#  إطار العمل: TensorFlow / Keras
#  تاريخ التوليد: {$this->date()}
# =============================================================

import tensorflow as tf
import numpy as np

# ─── إعدادات التجربة ─────────────────────────────────────────
EPOCHS        = {$epochs}
BATCH_SIZE    = {$bs}
LEARNING_RATE = {$lr}

print(f"📱 GPUs متاحة: {'{len(tf.config.list_physical_devices(\"GPU\"))}'}")

# ─── النموذج: {$name} ─────────────────────────────────────────
{$modelExample}

# ─── تجميع النموذج ────────────────────────────────────────────
model.compile(
    optimizer={$optCode},
    loss={$lossCode},
    metrics=['accuracy']
)

model.summary()

# ─── تحميل البيانات (عدّل هنا مسار بياناتك) ──────────────────
def load_data():
    # مثال: استبدل هذا بتحميل بياناتك الفعلية
    X = np.random.randn(1000, 10).astype(np.float32)
    y = np.random.randint(0, 2, 1000).astype(np.int64)
    split = int(0.8 * len(X))
    return (X[:split], y[:split]), (X[split:], y[split:])

(X_train, y_train), (X_val, y_val) = load_data()

# ─── Callbacks ───────────────────────────────────────────────
callbacks = [
    tf.keras.callbacks.EarlyStopping(patience=10, restore_best_weights=True),
    tf.keras.callbacks.ReduceLROnPlateau(factor=0.5, patience=5),
    tf.keras.callbacks.ModelCheckpoint(
        "model_{$this->slug($name)}_best.h5",
        save_best_only=True, monitor='val_loss'
    ),
]

# ─── التدريب ──────────────────────────────────────────────────
history = model.fit(
    X_train, y_train,
    validation_data=(X_val, y_val),
    epochs=EPOCHS,
    batch_size=BATCH_SIZE,
    callbacks=callbacks,
    verbose=1,
)

# ─── حفظ النموذج ─────────────────────────────────────────────
model.save("model_{$this->slug($name)}.keras")
print("✅ تم حفظ النموذج في model_{$this->slug($name)}.keras")
PYTHON;
    }

    private function defaultPytorchModel(string $name): string
    {
        return <<<PY
class Model(nn.Module):
    \"\"\"نموذج {$name} الافتراضي — عدّله ليناسب بياناتك\"\"\"
    def __init__(self, input_dim=10, hidden_dim=128, output_dim=2):
        super().__init__()
        self.net = nn.Sequential(
            nn.Linear(input_dim, hidden_dim),
            nn.ReLU(),
            nn.Dropout(0.3),
            nn.Linear(hidden_dim, hidden_dim),
            nn.ReLU(),
            nn.Linear(hidden_dim, output_dim),
        )

    def forward(self, x):
        return self.net(x)
PY;
    }

    private function defaultKerasModel(string $name): string
    {
        return <<<PY
# نموذج {$name} الافتراضي — عدّله ليناسب بياناتك
model = tf.keras.Sequential([
    tf.keras.layers.Dense(128, activation='relu', input_shape=(10,)),
    tf.keras.layers.Dropout(0.3),
    tf.keras.layers.Dense(128, activation='relu'),
    tf.keras.layers.Dense(2, activation='softmax'),
])
PY;
    }

    private function date(): string
    {
        return now()->format('Y-m-d H:i');
    }

    private function slug(string $name): string
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $name));
    }
}
