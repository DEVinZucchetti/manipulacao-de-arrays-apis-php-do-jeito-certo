
CREATE TYPE status_reviews AS ENUM ('PENDENTE', 'APROVADO', 'REJEITADO');

CREATE TABLE places (
  id SERIAL PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  contact VARCHAR(20),
  opening_hours VARCHAR(100),
  description TEXT,
  latitude FLOAT NOT NULL UNIQUE,
  longitude FLOAT NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE reviews (
  id SERIAL PRIMARY KEY,
  place_id INTEGER NOT NULL REFERENCES places(id),
  name TEXT NOT NULL,
  email VARCHAR(150),
  stars DECIMAL(2,1),
  date TIMESTAMP,
  status status_reviews DEFAULT 'PENDENTE',
  created_at TIMESTAMP DEFAULT NOW(),
  FOREIGN KEY (place_id) REFERENCES places(id)
);
