# USAGE
# python3 train.py --dataset datasets-generate --model model/plastic.model --label-bin model/plastic_lb.pickle --plot model/plastic_plot.png

# mengatur backend matplotlib sehingga angka dapat disimpan di background
import matplotlib
matplotlib.use("Agg")

# impor paket yang diperlukan
from sklearn.preprocessing import LabelBinarizer
from sklearn.model_selection import train_test_split
from sklearn.metrics import classification_report
from tensorflow.keras import backend as K
from tensorflow.keras.models import Sequential, load_model
from tensorflow.keras.layers import Dense, RepeatVector, Masking, TimeDistributed
from tensorflow.keras.optimizers import SGD
from tensorflow.keras.utils import plot_model

from imutils import paths
from cv2 import cv2
import matplotlib.pyplot as plt
import numpy as np
import argparse
import random
import pickle
import os
import warnings

# menyembunyikan error
warnings.filterwarnings('ignore')

# membangun parser argumen dan parsing argumen
ap = argparse.ArgumentParser()
ap.add_argument("-d", "--dataset", required=True,
	help="path to input dataset of images")
ap.add_argument("-m", "--model", required=True,
	help="path to output trained model")
ap.add_argument("-l", "--label-bin", required=True,
	help="path to output label binarizer")
ap.add_argument("-p", "--plot", required=True,
	help="path to output accuracy/loss plot")
args = vars(ap.parse_args())

# inisialisasi data dan label
print("[INFO] loading images...")
data = []
labels = []

# ambil jalur gambar dan merandom gambar secara acak
imagePaths = sorted(list(paths.list_images(args["dataset"])))
random.seed(42)
random.shuffle(imagePaths)

# loop di atas gambar input
for imagePath in imagePaths:
	# memuat gambar, mengubah ukuran gambar menjadi 32x32 piksel (mengabaikan aspek rasio), 
	# meratakan gambar menjadi 32x32x3 = 3072 piksel gambar ke dalam daftar, 
	# dan menyimpan gambar dalam daftar data
	print("[INFO] Preproccessing image: ", imagePath)
	image = cv2.imread(imagePath)
	image = cv2.resize(image, (32, 32)).flatten()
	data.append(image)

	# ekstrak label kelas dari jalur gambar dan memperbarui daftar label
	label = imagePath.split(os.path.sep)[-2]
	labels.append(label)

# skala intensitas piksel mentah ke kisaran [0, 1]
data = np.array(data, dtype="float") / 255.0
labels = np.array(labels)

# mempartisi data menjadi pelatihan dan pengujian split menggunakan 75% dari data untuk pelatihan 
# dan sisanya 25% untuk pengujian
(trainX, testX, trainY, testY) = train_test_split(data,
	labels, test_size=0.25, random_state=42)

# mengonversi label dari bilangan bulat ke vektor (untuk klasifikasi biner 2-kelas, 
# disini harus menggunakan fungsi 'to_categorical karena LabelBinarizer 
# dari scikit-learn tidak akan mengembalikan vektor)
lb = LabelBinarizer()
trainY = lb.fit_transform(trainY)
testY = lb.transform(testY)

# Backpropagation
# menentukan arsitektur 3072-1024-512-7 menggunakan Keras
# lapisan input dan lapisan tersembunyi pertama didefinisikan pada Baris 91. 
# akan memiliki input_shape 3072 karena ada 32x32x3 = 3072 piksel dalam gambar input yang diratakan.
# lapisan tersembunyi pertama akan memiliki 1024 node.
# lapisan tersembunyi kedua akan memiliki 512 node (Baris 92).
# akhirnya, jumlah node di lapisan keluaran akhir (Baris 93) akan menjadi jumlah label kelas yang mungkin. 
# dalam hal ini, lapisan output akan memiliki 7 node (HDPE, LDPE, Other, PET, PP, PS, PVC).
model = Sequential()
model.add(Dense(1024, input_shape=(3072,), activation="sigmoid"))
model.add(Dense(512, activation="sigmoid"))
model.add(Dense(len(lb.classes_), activation="softmax"))

# inisialisasi tingkat pembelajaran awal dan epoch/iterasi untuk pelatihan
INIT_LR = 0.01
EPOCHS = 75

# kompilasi model menggunakan SGD sebagai pengoptimal dan categorical 
# cross-entropy loss (menggunakan binary_crossentropy untuk klasifikasi 2 kelas)
# dimana SGD adalah pengoptimalan library yang menggunakan backpropagation
print("[INFO] training network...")
opt = SGD(lr=INIT_LR)
model.compile(loss="categorical_crossentropy", optimizer=opt,
	metrics=["accuracy"])

# melatih jaringan saraf
H = model.fit(trainX, trainY, validation_data=(testX, testY),
	epochs=EPOCHS, batch_size=32)

# mengevaluasi jaringan
print("[INFO] evaluating network...")
predictions = model.predict(testX, batch_size=32)
print(classification_report(testY.argmax(axis=1),
	predictions.argmax(axis=1), target_names=lb.classes_))

# merencanakan kehilangan dan akurasi pelatihan
N = np.arange(0, EPOCHS)
plt.style.use("ggplot")
plt.figure()
plt.plot(N, H.history["loss"], label="train_loss")
plt.plot(N, H.history["val_loss"], label="val_loss")
plt.plot(N, H.history["accuracy"], label="train_acc")
plt.plot(N, H.history["val_accuracy"], label="val_acc")
plt.title("Training Loss and Accuracy (Backpropagation NN)")
plt.xlabel("Epoch #")
plt.ylabel("Loss/Accuracy")
plt.legend()
plt.savefig(args["plot"])

# simpan model dan beri label binarizer ke disk
print("[INFO] serializing network and label binarizer...")
model.save(args["model"])
f = open(args["label_bin"], "wb")
f.write(pickle.dumps(lb))
f.close()