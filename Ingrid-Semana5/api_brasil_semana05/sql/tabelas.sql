CREATE TABLE IF NOT EXISTS public.places
(
    id integer NOT NULL DEFAULT nextval('places_id_seq'::regclass),
    name text COLLATE pg_catalog."default",
    contact text COLLATE pg_catalog."default",
    opening_hours text COLLATE pg_catalog."default",
    description text COLLATE pg_catalog."default",
    latitude numeric,
    longitude numeric,
    CONSTRAINT places_pkey PRIMARY KEY (id)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.places
    OWNER to postgres;



CREATE TABLE IF NOT EXISTS public.reviews
(
    id integer NOT NULL DEFAULT nextval('reviews_id_seq'::regclass),
    name text COLLATE pg_catalog."default",
    email text COLLATE pg_catalog."default",
    stars numeric,
    date timestamp with time zone,
    place_id integer,
    status text COLLATE pg_catalog."default",
    CONSTRAINT reviews_pkey PRIMARY KEY (id)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.reviews
    OWNER to postgres;