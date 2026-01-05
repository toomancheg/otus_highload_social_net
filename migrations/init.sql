
CREATE TABLE IF NOT EXISTS users (
                                     id SERIAL PRIMARY KEY,
                                     email VARCHAR(255) UNIQUE NOT NULL,
                                     password_hash VARCHAR(255) NOT NULL,
                                     first_name VARCHAR(100) NOT NULL,
                                     last_name VARCHAR(100) NOT NULL,
                                     birth_date DATE NOT NULL,
                                     gender VARCHAR(20) NOT NULL,
                                     interests TEXT,
                                     city VARCHAR(100),
                                     country VARCHAR(100),
                                     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Комментарии к таблице и колонкам
COMMENT ON TABLE users IS 'Таблица пользователей социальной сети';
COMMENT ON COLUMN users.email IS 'Электронная почта пользователя';
COMMENT ON COLUMN users.password_hash IS 'Хэш пароля';
COMMENT ON COLUMN users.first_name IS 'Имя пользователя';
COMMENT ON COLUMN users.last_name IS 'Фамилия пользователя';
COMMENT ON COLUMN users.birth_date IS 'Дата рождения';
COMMENT ON COLUMN users.gender IS 'Пол пользователя';
COMMENT ON COLUMN users.interests IS 'Интересы пользователя';
COMMENT ON COLUMN users.city IS 'Город';
COMMENT ON COLUMN users.country IS 'Страна';