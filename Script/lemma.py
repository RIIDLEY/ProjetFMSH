import spacy
import sys
nlp = spacy.load('fr_core_news_md')

i=1
VariableRetour = ""
while(i<len(sys.argv)):
    doc = nlp(sys.argv[i])

    for token in doc:
        VariableRetour += token.lemma_ + "|"
    i+=1

print(VariableRetour)