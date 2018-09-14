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
    title character varying(255) NOT NULL,
    price real
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
    post_title character varying(255) NOT NULL
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
    name character varying(255) NOT NULL,
    profile_id integer
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
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
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

COPY books (id, title, price) FROM stdin;
1	test book	15.2200003
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
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
1	1
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
26	1
26	1
29	1
29	1
29	2
32	1
35	1
35	2
1	1
1	2
2	2
2	1
\.


--
-- Data for Name: category; Type: TABLE DATA; Schema: public; Owner: root
--

COPY category (id, title) FROM stdin;
1	test category
2	new category
3	new category 2
4	new category 4
\.


--
-- Name: category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('category_id_seq', 1, true);


--
-- Data for Name: post; Type: TABLE DATA; Schema: public; Owner: root
--

COPY post (id, author_id, editor_id, is_published, date_published, post_title) FROM stdin;
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
24	1	1	f	2018-05-25 14:17:50	New post
25	1	1	f	2018-05-25 14:17:50	New post
26	1	1	t	2018-05-25 14:17:50	some title
27	1	1	f	2018-05-25 14:19:48	New post
28	1	1	f	2018-05-25 14:19:48	New post
57	1	1	f	2018-08-28 15:46:03	New post
58	1	1	f	2018-08-28 15:46:03	New post
29	1	1	t	2018-05-25 14:19:48	some title
30	1	1	f	2018-08-28 15:42:48	New post
31	1	1	f	2018-08-28 15:42:48	New post
32	1	1	t	2018-08-28 15:42:48	some title
33	1	1	f	2018-08-28 15:42:53	New post
34	1	1	f	2018-08-28 15:42:53	New post
35	1	1	t	2018-08-28 15:42:53	some title
36	1	1	f	2018-08-28 15:43:02	New post
37	1	1	f	2018-08-28 15:43:02	New post
60	1	1	f	2018-08-28 15:50:37	New post
61	1	1	f	2018-08-28 15:50:37	New post
39	1	1	f	2018-08-28 15:43:22	New post
40	1	1	f	2018-08-28 15:43:22	New post
42	1	1	f	2018-08-28 15:43:35	New post
43	1	1	f	2018-08-28 15:43:35	New post
63	1	1	f	2018-08-28 15:50:39	New post
64	1	1	f	2018-08-28 15:50:39	New post
45	1	1	f	2018-08-28 15:43:36	New post
46	1	1	f	2018-08-28 15:43:36	New post
48	1	1	f	2018-08-28 15:43:37	New post
49	1	1	f	2018-08-28 15:43:37	New post
1	5	5	f	2018-05-25 14:06:23	New post
2	5	5	f	2018-05-25 14:06:23	New post
3	5	5	f	2018-05-25 14:07:26	New post
51	1	1	f	2018-08-28 15:43:51	New post
52	1	1	f	2018-08-28 15:43:51	New post
4	5	5	f	2018-05-25 14:07:26	New post
5	5	5	t	2018-05-25 14:07:26	some title
6	5	5	f	2018-05-25 14:08:01	New post
54	1	1	f	2018-08-28 15:43:52	New post
55	1	1	f	2018-08-28 15:43:52	New post
7	5	5	f	2018-05-25 14:08:01	New post
8	5	5	t	2018-05-25 14:08:01	some title
66	5	5	f	2018-08-31 15:04:38	New post
67	1	1	f	2018-08-31 15:04:38	New post
69	5	5	f	2018-08-31 15:04:40	New post
70	1	1	f	2018-08-31 15:04:40	New post
72	5	5	f	2018-08-31 15:04:42	New post
73	1	1	f	2018-08-31 15:04:42	New post
\.


--
-- Name: post_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('post_id_seq', 74, true);


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
9	1	test	test
10	1	test	test
11	1	test	test
12	1	test	test
13	1	test	test
14	1	test	test
15	1	test	test
16	1	test	test
17	1	test	test
18	1	test	test
19	1	test	test
20	1	test	test
21	1	test	test
22	1	test	test
23	1	test	test
24	1	test	test
25	1	test	test
26	1	test	test
27	1	test	test
28	1	test	test
29	5	test	test
30	5	test	test
31	5	test	test
\.


--
-- Name: user_profile_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('user_profile_id_seq', 31, true);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: root
--

COPY users (id, name, profile_id) FROM stdin;
5	New name	\N
6	New name	\N
7	New name	\N
8	New name	\N
9	New name	\N
10	New name	\N
11	Test name	\N
12	New name	\N
13	Test name	\N
14	New name	\N
15	6d21c595fc59997	\N
16	New name	\N
17	b46ee365b6e3aa4	\N
18	New name	\N
19	84cdc327304049d	\N
20	New name	\N
21	ee024e77ab50025	\N
22	New name	\N
23	299178ee27a2c7c	\N
24	New name	\N
25	a57fd6092b0fccb	\N
26	New name	\N
27	73ecab86e6315d8	\N
28	New name	\N
29	0aa4ac8b7f4527b	\N
30	New name	\N
31	b3abfd231dbb926	\N
32	New name	\N
33	891601163abe404	\N
34	New name	\N
35	e5019798de7e18f	\N
36	New name	\N
37	5eac0eefbba1872	\N
38	New name	\N
39	d99b5fe15828bd1	\N
40	New name	\N
41	0fb991997060a1c	\N
42	New name	\N
43	b3b2544f05bf572	\N
44	New name	\N
45	325bfe24ce08586	\N
46	New name	\N
47	17660a9a8d2b85d	\N
48	New name	\N
49	468fa80cef30e59	\N
50	New name	\N
1	New name	2
2	New name	3
3	New name	2
4	New name	3
51	8c6ee01c2b76c68	\N
52	New name	\N
53	3b60658f97b4c78	\N
54	New name	\N
55	83d1c4c5fc6419c	\N
56	New name	\N
\.


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('users_id_seq', 56, true);


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

