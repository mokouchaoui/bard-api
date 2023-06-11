import sys
from bardapi import Bard

TOKEN = 'XQi1ii7TkB8nNQgbkX-SVlc3mrA7UWTWACu3oRrxQvM4U4aKiO8dm3OgpaUFh7vptNmv8Q.'
INPUT_TEMPLATE = "You are a very factual, verbose, and helpful sales assistant that generates product description in {language} language. Generate the product description in 120 words for the following product: {keyboard}"

# Initialize Bard API
bard = Bard(token=TOKEN)

# Extract command-line arguments
keyboard = sys.argv[1]
language = sys.argv[2]

# Create input text using the template
input_text = INPUT_TEMPLATE.format(language=language, keyboard=keyboard)

# Get the capitalized text from Bard API
capitalized_text = bard.get_answer(input_text)['content']

# Remove the greeting sentence
greeting_sentence = "Sure, here is a product description for a Moroccan jellaba in English, 120 words without a greeting sentence:"
if capitalized_text.startswith(greeting_sentence):
    capitalized_text = capitalized_text[len(greeting_sentence):]

# Print the capitalized text
print(capitalized_text)
