[M2S04]: Modulo 2 -> Semana 04
/* RUN - Banco de dados com Docker:*/
docker run
--name places
-e POSTGRESQL_USERNAME=admin
-e POSTGRESQL_PASSWORD=admin
-e POSTGRESQL_DATABASE=api_places_peru
-p 5432:5432
bitnami/postgresql


/* CREATE - Tabela Places */
CREATE TABLE "places" (
"id" serial PRIMARY KEY,
"name" varchar(150) NOT NULL,
"contact" varchar(20),
"opening_hours" varchar(100),
"description" text,
"latitude" float UNIQUE NOT NULL,
"longitude" float UNIQUE NOT NULL,
"created_at" timestamp with time zone DEFAULT now()
);


/* CREATE - Tabela Reviews */
create type status_review as enum ('PENDENTE', 'APROVADO',
'REJEITADO');

CREATE TABLE "reviews" (
"id" serial PRIMARY KEY,
"place_id" integer,
"name" text not null,
"email" varchar(150),
"stars" decimal (2,
1),
"date" timestamp,
"status" status_review default 'PENDENTE',
"created_at" timestamp with time zone default now(),

FOREIGH KEY ("place_id") references "places" ("id")
);

/* INSERT - Places */
INSERT INTO places (
"name",
"contact",
"opening_hours",
"description",
"latitude",
"longitude")
values ("Rua Franklin Fontes",
"36572-182",
"Aberto das 8h às 18h",
"...................",
-1.111111111,
1.111111111,
);

/* SELECT - Places */
SELECT * FROM places
SELECT * FROM places WHERE id=1;


/* DELETE - Places */
DELETE FROM places WHERE id = 3

/* UPDATE - Places */
UPDATE places
set description = "Lugar arborizado",
opening_hours = "Funcionamento das 9h "
where id=1;

/* INSERT - REVIEWS */
INSERT INTO reviews (
place_id,
name,
email,
stars,
date)
VALUES (
1,
'Marcel',
'gmail@email.com',
5,
'2023-12-30 12:00:00'
);

/* SELECT - REVIEWS */
SELECT r.id, r.place_id, r.name, r.email, r.stars, r.date, p.name AS
place_name
FROM reviews AS r
JOIN places AS p ON r.place_id = p.id
WHERE r.place_id = 1;
