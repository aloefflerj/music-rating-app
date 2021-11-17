### DB Model

users($id, username, mail, passwd, type)

songs($id, title)

albums($id, title)

artists($id, name)

artists_songs($id, #artist, #song)

artists_albums($id, #artist, #album)

albums_songs($id, #album, #song)

starred_songs($id, stars, #song, #user)

starred_albums($id, stars, #album, #user)

starred_artists($id, stars, #artist, #user)
