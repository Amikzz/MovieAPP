# MovieApp – Your Ultimate Movie & TV Show Explorer  

**Built with Laravel & SQL | Powered by TMDB API**

---

## 🚀 Overview  

**MovieApp** is a full-featured movie and TV show discovery platform that delivers a complete cinematic experience for users.  
Powered by the **TMDB (The Movie Database) API**, this web application allows users to:  
- Search for movies and TV shows  
- View detailed information including ratings, cast, genres, and trailers  
- Receive smart recommendations based on similar titles  
- Add movies and TV shows to their personal **favorites list**  

This project demonstrates seamless **API integration**, **database management**, and a **clean Laravel MVC architecture**, creating a smooth and engaging movie discovery experience.

---

## ✨ Key Features  

- 🎥 **TMDB API Integration** – Fetch real-time movie and TV data directly from The Movie Database  
- 🔍 **Advanced Search** – Search by movie title, genre, or TV show name  
- 📺 **Recommendations** – Explore related and trending titles dynamically  
- ⭐ **Favorites System** – Add or remove movies and TV shows to your favorites list (stored in SQL)  
- 🎞️ **Trailers** – Watch official trailers embedded within the app  
- 🧩 **Responsive UI** – Clean and modern interface for desktop and mobile  
- ⚙️ **Laravel Framework** – Robust backend with secure routing and API handling  

---

## 🧠 Tech Stack  

| Layer | Technology |
|:------|:------------|
| **Frontend** | Blade Templates, Tailwind CSS (optional) |
| **Backend** | Laravel 11 |
| **Database** | MySQL / SQL Server |
| **API Integration** | TMDB (The Movie Database API) |
| **Authentication** | Laravel Breeze / Jetstream (if implemented) |

---

## 🗂️ Project Structure  

```
MovieApp/
├── app/
│   ├── Http/
│   ├── Models/
│   ├── Controllers/
│   └── ...
├── resources/
│   └── views/
│       ├── home.blade.php
│       ├── movie-details.blade.php
│       └── favorites.blade.php
├── routes/
│   └── web.php
├── public/
│   └── assets/
├── database/
│   └── migrations/
└── README.md
```


---

## ⚙️ Setup & Installation  

### Prerequisites  
- PHP ≥ 8.2  
- Composer  
- MySQL or SQL Server  
- TMDB API Key (you can create one from [https://www.themoviedb.org](https://www.themoviedb.org))  

### Steps  

1. **Clone the Repository**  
   ```bash
   git clone https://github.com/yourusername/MovieApp.git
   cd MovieApp
   ```
   
2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Set Up Environment Variables**
   Create a .env file and add your TMDB API key and database details:
   ```bash
   TMDB_API_KEY=your_tmdb_api_key_here
   DB_CONNECTION=mysql
   DB_DATABASE=movieapp
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Run Migrations**
   ```bash
   php artisan migrate
   ```

5. **Serve the Application**
   ```bash
   php artisan serve
   ```

6. Access the App
   Visit http://localhost:8000

## 💡 Future Improvements  

- Add user authentication for personalized experiences  
- Implement watchlists and movie ratings  
- Enhance UI using Vue.js or React frontend integration  
- Add pagination and infinite scroll for large datasets  
- Introduce dark/light theme toggle  

---

## 🤝 Contributing  

Contributions are welcome!  
If you’d like to improve this project, please **fork the repository** and submit a **pull request**.

---

## 📸 Screenshots



---

## 📬 Contact  

**👩‍💻 Developer:** Amika

**📧 Email:** amikasubasinghe@gmail.com

---
