"
Test 001
"
Trait {
	#name : #FilesQuickParser,
	#category : #'Zeppi-Snippets'
}

{ #category : #files }
FilesQuickParser >> quickParser001 [
"
# A quick parser

## Problem

We have a liste of transaction in text file and this file will be imported by a
specific program. A list of transaction is wrong because somes transaction are
mixed. Type A and B shoulde be in separate files.

We had exported a liste of identifier of Type A and now with this files we
would create this two files.

## Solution

With the identifier file, create an array who sould be used to search in the
transaction file if is Type A or B
"
| identifiers |

'identifiers.csv' asFileReference readStreamDo: [ :str |
	identifiers := Set new.
	[ str atEnd ] whileFalse: [ identifiers add: str nextLine trim].
   str close.
].

"
Process the transaction file
"
'transactions.csv' asFileReference readStreamDo: [ :str |
  | file_match file_unlike |

  file_match := FileStream forceNewFileNamed: 'match.csv'.
  file_unlike := FileStream forceNewFileNamed: 'unlike.csv'.

	[ str atEnd ] whileFalse: [
    | line |
		line := str nextLine.

		identifiers detect: [ :token | line matchesRegex: '.*', token, '.*']
	     ifFound: [file_match nextPutAll: line;  nextPut: (Character lf)]
 	     ifNone: [file_unlike nextPutAll: line;  nextPut: (Character lf)].
	].

  file_match close.
  file_unlike close.

  str close.
].

SmalltalkImage current snapshot: true andQuit: true

]
