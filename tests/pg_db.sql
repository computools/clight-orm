--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: authors; Type: TABLE; Schema: public; Owner: root; Tablespace: 
--

CREATE TABLE authors (
    id integer NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE public.authors OWNER TO root;

--
-- Name: authors_books; Type: TABLE; Schema: public; Owner: root; Tablespace: 
--

CREATE TABLE authors_books (
    book_id integer NOT NULL,
    author_id integer NOT NULL
);


ALTER TABLE public.authors_books OWNER TO root;

--
-- Name: authors_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE authors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.authors_id_seq OWNER TO root;

--
-- Name: authors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE authors_id_seq OWNED BY authors.id;


--
-- Name: books; Type: TABLE; Schema: public; Owner: root; Tablespace: 
--

CREATE TABLE books (
    id integer NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE public.books OWNER TO root;

--
-- Name: books_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE books_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.books_id_seq OWNER TO root;

--
-- Name: books_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE books_id_seq OWNED BY books.id;


--
-- Name: books_theme; Type: TABLE; Schema: public; Owner: root; Tablespace: 
--

CREATE TABLE books_theme (
    theme_id integer NOT NULL,
    book_id integer NOT NULL
);


ALTER TABLE public.books_theme OWNER TO root;

--
-- Name: categorization; Type: TABLE; Schema: public; Owner: root; Tablespace: 
--

CREATE TABLE categorization (
    post_id integer NOT NULL,
    category_id integer NOT NULL
);


ALTER TABLE public.categorization OWNER TO root;

--
-- Name: category; Type: TABLE; Schema: public; Owner: root; Tablespace: 
--

CREATE TABLE category (
    id integer NOT NULL,
    title character varying(255) NOT NULL
);


ALTER TABLE public.category OWNER TO root;

--
-- Name: category_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE category_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.category_id_seq OWNER TO root;

--
-- Name: category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE category_id_seq OWNED BY category.id;


--
-- Name: post; Type: TABLE; Schema: public; Owner: root; Tablespace: 
--

CREATE TABLE post (
    id integer NOT NULL,
    author_id integer,
    editor_id integer,
    is_published boolean NOT NULL,
    date_published timestamp(0) without time zone NOT NULL,
    title character varying(255) NOT NULL
);


ALTER TABLE public.post OWNER TO root;

--
-- Name: post_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE post_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.post_id_seq OWNER TO root;

--
-- Name: post_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE post_id_seq OWNED BY post.id;


--
-- Name: theme; Type: TABLE; Schema: public; Owner: root; Tablespace: 
--

CREATE TABLE theme (
    id integer NOT NULL,
    title character varying(255) NOT NULL
);


ALTER TABLE public.theme OWNER TO root;

--
-- Name: theme_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE theme_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.theme_id_seq OWNER TO root;

--
-- Name: theme_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE theme_id_seq OWNED BY theme.id;


--
-- Name: user_profile; Type: TABLE; Schema: public; Owner: root; Tablespace: 
--

CREATE TABLE user_profile (
    id integer NOT NULL,
    user_id integer,
    first_name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL
);


ALTER TABLE public.user_profile OWNER TO root;

--
-- Name: user_profile_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE user_profile_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_profile_id_seq OWNER TO root;

--
-- Name: user_profile_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE user_profile_id_seq OWNED BY user_profile.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: root; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE public.users OWNER TO root;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO root;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY authors ALTER COLUMN id SET DEFAULT nextval('authors_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY books ALTER COLUMN id SET DEFAULT nextval('books_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY category ALTER COLUMN id SET DEFAULT nextval('category_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY post ALTER COLUMN id SET DEFAULT nextval('post_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY theme ALTER COLUMN id SET DEFAULT nextval('theme_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY user_profile ALTER COLUMN id SET DEFAULT nextval('user_profile_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- Data for Name: authors; Type: TABLE DATA; Schema: public; Owner: root
--

COPY authors (id, name) FROM stdin;
1	test author
\.


--
-- Data for Name: authors_books; Type: TABLE DATA; Schema: public; Owner: root
--

COPY authors_books (book_id, author_id) FROM stdin;
1	1
1	1
1	1
1	1
1	1
\.


--
-- Name: authors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('authors_id_seq', 1, true);


--
-- Data for Name: books; Type: TABLE DATA; Schema: public; Owner: root
--

COPY books (id, name) FROM stdin;
1	test book
\.


--
-- Name: books_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('books_id_seq', 1, true);


--
-- Data for Name: books_theme; Type: TABLE DATA; Schema: public; Owner: root
--

COPY books_theme (theme_id, book_id) FROM stdin;
1	1
1	1
1	1
1	1
1	1
\.


--
-- Data for Name: categorization; Type: TABLE DATA; Schema: public; Owner: root
--

COPY categorization (post_id, category_id) FROM stdin;
5	1
8	1
8	1
11	1
11	1
14	1
14	1
17	1
17	1
20	1
20	1
23	1
23	1
\.


--
-- Data for Name: category; Type: TABLE DATA; Schema: public; Owner: root
--

COPY category (id, title) FROM stdin;
1	test category
\.


--
-- Name: category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('category_id_seq', 1, true);


--
-- Data for Name: post; Type: TABLE DATA; Schema: public; Owner: root
--

COPY post (id, author_id, editor_id, is_published, date_published, title) FROM stdin;
1	1	1	f	2018-05-25 14:06:23	New post
2	1	1	f	2018-05-25 14:06:23	New post
3	1	1	f	2018-05-25 14:07:26	New post
4	1	1	f	2018-05-25 14:07:26	New post
5	1	1	t	2018-05-25 14:07:26	some title
6	1	1	f	2018-05-25 14:08:01	New post
7	1	1	f	2018-05-25 14:08:01	New post
8	1	1	t	2018-05-25 14:08:01	some title
9	1	1	f	2018-05-25 14:08:11	New post
10	1	1	f	2018-05-25 14:08:11	New post
11	1	1	t	2018-05-25 14:08:11	some title
12	1	1	f	2018-05-25 14:08:22	New post
13	1	1	f	2018-05-25 14:08:22	New post
14	1	1	t	2018-05-25 14:08:22	some title
15	1	1	f	2018-05-25 14:08:25	New post
16	1	1	f	2018-05-25 14:08:25	New post
17	1	1	t	2018-05-25 14:08:25	some title
18	1	1	f	2018-05-25 14:08:28	New post
19	1	1	f	2018-05-25 14:08:28	New post
20	1	1	t	2018-05-25 14:08:28	some title
21	1	1	f	2018-05-25 14:10:02	New post
22	1	1	f	2018-05-25 14:10:02	New post
23	1	1	t	2018-05-25 14:10:02	some title
\.


--
-- Name: post_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('post_id_seq', 23, true);


--
-- Data for Name: theme; Type: TABLE DATA; Schema: public; Owner: root
--

COPY theme (id, title) FROM stdin;
1	test theme
\.


--
-- Name: theme_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('theme_id_seq', 1, true);


--
-- Data for Name: user_profile; Type: TABLE DATA; Schema: public; Owner: root
--

COPY user_profile (id, user_id, first_name, last_name) FROM stdin;
1	\N	test	test
2	1	test	test
3	1	test	test
4	1	test	test
5	1	test	test
6	1	test	test
7	1	test	test
8	1	test	test
\.


--
-- Name: user_profile_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('user_profile_id_seq', 8, true);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: root
--

COPY users (id, name) FROM stdin;
1	New name
2	New name
3	New name
4	New name
5	New name
6	New name
7	New name
8	New name
\.


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('users_id_seq', 8, true);


--
-- Name: authors_pkey; Type: CONSTRAINT; Schema: public; Owner: root; Tablespace: 
--

ALTER TABLE ONLY authors
    ADD CONSTRAINT authors_pkey PRIMARY KEY (id);


--
-- Name: books_pkey; Type: CONSTRAINT; Schema: public; Owner: root; Tablespace: 
--

ALTER TABLE ONLY books
    ADD CONSTRAINT books_pkey PRIMARY KEY (id);


--
-- Name: category_pkey; Type: CONSTRAINT; Schema: public; Owner: root; Tablespace: 
--

ALTER TABLE ONLY category
    ADD CONSTRAINT category_pkey PRIMARY KEY (id);


--
-- Name: post_pkey; Type: CONSTRAINT; Schema: public; Owner: root; Tablespace: 
--

ALTER TABLE ONLY post
    ADD CONSTRAINT post_pkey PRIMARY KEY (id);


--
-- Name: theme_pkey; Type: CONSTRAINT; Schema: public; Owner: root; Tablespace: 
--

ALTER TABLE ONLY theme
    ADD CONSTRAINT theme_pkey PRIMARY KEY (id);


--
-- Name: user_profile_pkey; Type: CONSTRAINT; Schema: public; Owner: root; Tablespace: 
--

ALTER TABLE ONLY user_profile
    ADD CONSTRAINT user_profile_pkey PRIMARY KEY (id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: root; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: idx_2dfda3cb16a2b381; Type: INDEX; Schema: public; Owner: root; Tablespace: 
--

CREATE INDEX idx_2dfda3cb16a2b381 ON authors_books USING btree (book_id);


--
-- Name: idx_2dfda3cbf675f31b; Type: INDEX; Schema: public; Owner: root; Tablespace: 
--

CREATE INDEX idx_2dfda3cbf675f31b ON authors_books USING btree (author_id);


--
-- Name: idx_3c8753f116a2b381; Type: INDEX; Schema: public; Owner: root; Tablespace: 
--

CREATE INDEX idx_3c8753f116a2b381 ON books_theme USING btree (book_id);


--
-- Name: idx_3c8753f159027487; Type: INDEX; Schema: public; Owner: root; Tablespace: 
--

CREATE INDEX idx_3c8753f159027487 ON books_theme USING btree (theme_id);


--
-- Name: idx_51c812b112469de2; Type: INDEX; Schema: public; Owner: root; Tablespace: 
--

CREATE INDEX idx_51c812b112469de2 ON categorization USING btree (category_id);


--
-- Name: idx_51c812b14b89032c; Type: INDEX; Schema: public; Owner: root; Tablespace: 
--

CREATE INDEX idx_51c812b14b89032c ON categorization USING btree (post_id);


--
-- Name: idx_5a8a6c8d6995ac4c; Type: INDEX; Schema: public; Owner: root; Tablespace: 
--

CREATE INDEX idx_5a8a6c8d6995ac4c ON post USING btree (editor_id);


--
-- Name: idx_5a8a6c8df675f31b; Type: INDEX; Schema: public; Owner: root; Tablespace: 
--

CREATE INDEX idx_5a8a6c8df675f31b ON post USING btree (author_id);


--
-- Name: fk_2dfda3cb16a2b381; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY authors_books
    ADD CONSTRAINT fk_2dfda3cb16a2b381 FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE;


--
-- Name: fk_2dfda3cbf675f31b; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY authors_books
    ADD CONSTRAINT fk_2dfda3cbf675f31b FOREIGN KEY (author_id) REFERENCES books(id) ON DELETE CASCADE;


--
-- Name: fk_3c8753f116a2b381; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY books_theme
    ADD CONSTRAINT fk_3c8753f116a2b381 FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE;


--
-- Name: fk_3c8753f159027487; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY books_theme
    ADD CONSTRAINT fk_3c8753f159027487 FOREIGN KEY (theme_id) REFERENCES theme(id) ON DELETE CASCADE;


--
-- Name: fk_51c812b112469de2; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY categorization
    ADD CONSTRAINT fk_51c812b112469de2 FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE;


--
-- Name: fk_51c812b14b89032c; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY categorization
    ADD CONSTRAINT fk_51c812b14b89032c FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE;


--
-- Name: fk_5a8a6c8d6995ac4c; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY post
    ADD CONSTRAINT fk_5a8a6c8d6995ac4c FOREIGN KEY (editor_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: fk_5a8a6c8df675f31b; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY post
    ADD CONSTRAINT fk_5a8a6c8df675f31b FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: fk_d95ab405a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY user_profile
    ADD CONSTRAINT fk_d95ab405a76ed395 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

