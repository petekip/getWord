This package extends the basic PHP files system functions with some simple but useful functions:

-fgetword acts exactly as fgetc or fgets but return instead a word from 
the chosen file resource

-yieldWordsFromFile is a generator useful to yield word from file until there is no more word left

-countWordsInFile use the yieldWordsFromFile generator and increment a counter to finally return the number of words in a file

-yieldWordsFromDir generator useful to yield word from directory until there is no more word left

-countWordsInDir use the yieldWordsFromDir generator and increment a counter to finally return the number of words in a directory

-wordsOccurrences return an array of words occurrences contained in a file or a directory  sorted alphabetically or by number of occurrences descending
or just by order of appearance.The returned value of this function is an array where each found file is linked to related words sorted as said above.
It can be useful mainly to index file and its contents.Each word is associated to the number of occurrences.

the different functions also get an optional filter argument:
Its purpose is to allow custom word filtering via callback
for example : 
	you can use a spell check database or any dictionary to make the class ignore some words
the design of such function is simple.It must only take one argument(the word) and must return true if the word
is accepted and false if it is rejected.
	
Indeed the package has its own definition of what is a word but this must not prevent you to specify
your criteria according to the goal your are trying to achieve.That is why the filter option exists.
You can of course extend the class to completely rewrite the fgetword function  or just use the code
as inspirational example to build something great.  
	
 # getWord
