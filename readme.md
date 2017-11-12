# zpan

Zpan is a beautiful radio management control panel written using Laravel. It has the following features:

- "Connection Info" page for DJs
- Request line
  - Bans by IP
- Radio timetable (by user's timezone)
- Events
  - Events require approval from a manager (or admin)
- vBulletin integration (you need a vBulletin users database to authenticate users from - shows the user's display name globally)

## API Endpoints

### GET /api/dj-says

Response:

```json
{
  "dj": "<div style=\"color: red;\">Jordan Doyle</div>",
  "msg": "Welcome to the radio!"
}
```

### GET /api/timetable

Response:

```json
[
  {
    "name": "Monday",
    "0": null,
    "1": null,
    "2": null,
    ...
    "13": "<div style=\"color: red\">Jordan Doyle</div>",
    "14": "Jordan Doyle",
    "15": null
    ...
  },
  {
    "name": "Tuesday",
    "0": null,
    "1": null,
    ...
    "10": "10am Tuesdays",
    "11": null,
    ...
  }
]
```

### GET /api/event/all

Response:

```json
[
  {
    "name": "Monday",
    "0": null,
    "1": null,
    "2": null,
    ...
    "13": "<div style=\"color: red\">The 1pm Event</div>",
    "15": null
  },
  ...
]
```

### GET /api/event/current

Response:

```json
{
  "id": 10,
  "name": "<div>Jordan Doyle</div>",
  "type": "Falling Furni",
  "room": 123456789,
  "booked": true
}
```

OR

```json
{
  "booked": false
}
```



### GET /api/stats

Response:

```json
{
  "listeners": 32,
  "raw_dj": "Jordan",
  "dj": "<span style=\"color: red\">Jordan</span>",
  "habbo": "jordanpotter",
  "artist": "Aystar",
  "song": "Behind Barz Freestyle"
}
```

OR

```json
{
  "status": false
}
```



### POST /api/request

Request:

```json
{
  "name": "Dave The Requester",
  "request": "Hey can you play Cotton Eyed Joe please?"
}
```

Response:

```json
{
  "type": "success",
  "msg": "Successfully submitted your request. We'll let the DJ know!"
}
```

### Installation

You must configure the [groups](https://github.com/w4/zpan/blob/master/app/Models/Group.php) to match your vBulletin setup. Then copy `.env.example` to `.env` and configure.

You can then run the migrations and gulp and you're ready to go!

```bash
php artisan migrate
gulp
```

