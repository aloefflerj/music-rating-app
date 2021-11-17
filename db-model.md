### DB Model

users($id, username, mail, passwd, type)

songs($id, title)

albums($id, title)

artists($id, name)

artists_songs($id, #artists, #songs)

artists_albums($id, #artists, #albums)

albums_songs($id, #albums, #songs)

starred_songs($id, stars, #song, #user)

starred_albums($id, stars, #album, #user)

starred_artists($id, stars, #artist, #user)
