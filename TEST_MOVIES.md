# Test Movies dengan Trailer

Berikut adalah beberapa Movie ID yang pasti memiliki trailer di TMDB:

## Popular Movies dengan Trailer

1. **Avatar: The Way of Water** (2022)
   - ID: `76600`
   - URL: http://localhost:8000/movies/76600
   - Multiple trailers available

2. **The Avengers** (2012)
   - ID: `24428`
   - URL: http://localhost:8000/movies/24428
   - Classic movie dengan banyak trailer

3. **Spider-Man: No Way Home** (2021)
   - ID: `634649`
   - URL: http://localhost:8000/movies/634649
   - Multiple trailers & teasers

4. **Inception** (2010)
   - ID: `27205`
   - URL: http://localhost:8000/movies/27205
   - Iconic trailers

5. **Interstellar** (2014)
   - ID: `157336`
   - URL: http://localhost:8000/movies/157336
   - Multiple trailers

## Recent Movies (2024)

6. **Deadpool & Wolverine** (2024)
   - ID: `533535`
   - URL: http://localhost:8000/movies/533535

7. **Dune: Part Two** (2024)
   - ID: `693134`
   - URL: http://localhost:8000/movies/693134

## Testing Instructions

1. Buka salah satu URL di atas
2. Cek Debug Panel (jika APP_DEBUG=true)
3. Verifikasi apakah:
   - Button "Tonton Trailer" menampilkan jumlah trailer
   - Section "Trailer & Video" muncul dengan thumbnails
   - Modal player berfungsi dengan baik

## Troubleshooting

Jika masih tidak ada data trailer:

1. **Cek API Key**: Pastikan TMDB API key valid di `.env`
2. **Cek Network**: Test API endpoint langsung:
   ```
   https://api.themoviedb.org/3/movie/76600/videos?api_key=YOUR_KEY&language=id-ID
   ```
3. **Cek Response**: Lihat Debug Panel untuk melihat actual response
4. **Clear Cache**: Jalankan `php artisan cache:clear`

## API Endpoint Test

Test manual dengan curl:
```bash
curl "https://api.themoviedb.org/3/movie/76600?api_key=YOUR_API_KEY&append_to_response=videos&language=id-ID"
```

## Expected Response Structure

```json
{
  "id": 76600,
  "title": "Avatar: The Way of Water",
  "videos": {
    "results": [
      {
        "id": "xxx",
        "key": "YOUTUBE_VIDEO_ID",
        "name": "Official Trailer",
        "site": "YouTube",
        "type": "Trailer",
        "size": 1080,
        "published_at": "2022-05-09T13:00:00.000Z"
      }
    ]
  }
}
```
