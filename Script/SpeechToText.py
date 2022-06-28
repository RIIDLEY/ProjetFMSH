import shutil
import subprocess
import speech_recognition as sr 
import os
import sys
from pydub import AudioSegment
from pydub.silence import split_on_silence
from pathlib import Path

r = sr.Recognizer()

def get_large_audio_transcription(path_file):

    command = "ffmpeg -i "+path_file+" -ab 160k -ac 2 -ar 44100 -vn Script/audio.wav"

    subprocess.call(command, shell=True)

    sound = AudioSegment.from_file("Script/audio.wav")

    #split l'audio lorsqu'il y'a une pause (silence)
    chunks = split_on_silence(sound,
        min_silence_len = 500,
        silence_thresh = sound.dBFS-14,
        keep_silence=500,
    )
    folder_name = "Script/audio-chunks"
    #print(len(chunks))
    if not os.path.isdir(folder_name):#crée le dossier où les chunks vont etre deposé
        os.mkdir(folder_name)

    texte_entier = ""
    nb_error=0

    for i, audio_chunk in enumerate(chunks, start=1):

        chunk_filename = os.path.join(folder_name, f"chunk{i}.wav")
        audio_chunk.export(chunk_filename, format="wav")

        with sr.AudioFile(chunk_filename) as source:
            audio_listened = r.record(source)
            try:
                texte_courant = r.recognize_google(audio_listened, language='fr')
            except sr.UnknownValueError as e:
                nb_error+=1
            else:
                texte_courant = f"{texte_courant.capitalize()}. "
                texte_courant+= "\n"
                #print(chunk_filename, ":", texte_courant)
                texte_entier += texte_courant

    shutil.rmtree("Script/audio-chunks")#supprime le dossier de chunk
    if os.path.exists("Script/audio.wav"):
        os.remove("Script/audio.wav")


    filename = Path(path_file).stem

    try:
        f = open("src/MediaToText/"+filename+".txt", "w")
        f.write(texte_entier)
        f.close()
        print(filename+".txt")
    except IOError:
        print("Error")

    #return texte_entier

get_large_audio_transcription(sys.argv[1])

