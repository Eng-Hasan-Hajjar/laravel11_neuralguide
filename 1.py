import tensorflow as tf  
model = tf.keras.Sequential([tf.keras.layers.Dense(128, activation='relu'),
                             tf.keras.layers.Dropout(0.3),tf.keras.layers.Dense(10, activation='softmax') ])  
model.compile(optimizer='adam', loss='sparse_categorical_crossentropy')