## [RockLog](http://rocklog.info.tm)

An application that saves songs played on [Classic Rock FM](http://www.rock.lt) and allows listening to them later.

Project built using the Laravel framework, some Google APIs and Materialize.

# Bugs and Improvements
- Fix MySQL access error #1045
- Write propper Laravel task for inserting new songs, rather than running raw SQL (I know I am asking for trouble here) - this will hopefully fix the broken insertion of songs that have a `'` in their name
- Add option to sign in with Google
- Add feature to save songs to YouTube playlist
- Add info section for each selected song/artist

### Todo  
`*` Connect to Firebase
