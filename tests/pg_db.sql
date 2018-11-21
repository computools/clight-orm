--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
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


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: authors; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.authors (
    id integer NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE public.authors OWNER TO postgres;

--
-- Name: authors_books; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.authors_books (
    book_id integer NOT NULL,
    author_id integer NOT NULL
);


ALTER TABLE public.authors_books OWNER TO postgres;

--
-- Name: authors_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.authors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.authors_id_seq OWNER TO postgres;

--
-- Name: authors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.authors_id_seq OWNED BY public.authors.id;


--
-- Name: books; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.books (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    price real,
    data json,
    data_binary jsonb
);


ALTER TABLE public.books OWNER TO postgres;

--
-- Name: books_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.books_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.books_id_seq OWNER TO postgres;

--
-- Name: books_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.books_id_seq OWNED BY public.books.id;


--
-- Name: books_theme; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.books_theme (
    theme_id integer NOT NULL,
    book_id integer NOT NULL
);


ALTER TABLE public.books_theme OWNER TO postgres;

--
-- Name: categorization; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.categorization (
    post_id integer NOT NULL,
    category_id integer NOT NULL
);


ALTER TABLE public.categorization OWNER TO postgres;

--
-- Name: category; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.category (
    id integer NOT NULL,
    title character varying(255) NOT NULL
);


ALTER TABLE public.category OWNER TO postgres;

--
-- Name: category_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.category_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.category_id_seq OWNER TO postgres;

--
-- Name: category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.category_id_seq OWNED BY public.category.id;


--
-- Name: post; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.post (
    id integer NOT NULL,
    author_id integer,
    editor_id integer,
    is_published boolean NOT NULL,
    date_published timestamp(0) without time zone NOT NULL,
    post_title character varying(255) NOT NULL
);


ALTER TABLE public.post OWNER TO postgres;

--
-- Name: post_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.post_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.post_id_seq OWNER TO postgres;

--
-- Name: post_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.post_id_seq OWNED BY public.post.id;


--
-- Name: theme; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.theme (
    id integer NOT NULL,
    title character varying(255) NOT NULL
);


ALTER TABLE public.theme OWNER TO postgres;

--
-- Name: theme_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.theme_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.theme_id_seq OWNER TO postgres;

--
-- Name: theme_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.theme_id_seq OWNED BY public.theme.id;


--
-- Name: user_profile; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.user_profile (
    id integer NOT NULL,
    user_id integer,
    first_name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL
);


ALTER TABLE public.user_profile OWNER TO postgres;

--
-- Name: user_profile_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_profile_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_profile_id_seq OWNER TO postgres;

--
-- Name: user_profile_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_profile_id_seq OWNED BY public.user_profile.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.users (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    profile_id integer
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.authors ALTER COLUMN id SET DEFAULT nextval('public.authors_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.books ALTER COLUMN id SET DEFAULT nextval('public.books_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.category ALTER COLUMN id SET DEFAULT nextval('public.category_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.post ALTER COLUMN id SET DEFAULT nextval('public.post_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.theme ALTER COLUMN id SET DEFAULT nextval('public.theme_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_profile ALTER COLUMN id SET DEFAULT nextval('public.user_profile_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: authors; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.authors (id, name) FROM stdin;
1	test author
\.


--
-- Data for Name: authors_books; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.authors_books (book_id, author_id) FROM stdin;
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
5	1
7	1
8	1
15	1
17	1
19	1
21	1
23	1
25	1
27	1
29	1
31	1
33	1
35	1
37	1
39	1
41	1
43	1
45	1
47	1
49	1
51	1
53	1
55	1
57	1
59	1
61	1
63	1
65	1
67	1
69	1
71	1
73	1
\.


--
-- Name: authors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.authors_id_seq', 1, true);


--
-- Data for Name: books; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.books (id, title, price, data, data_binary) FROM stdin;
55	some title	15.2200003	null	null
56	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
1	test book	15.2200003	\N	\N
4	some title	123	\N	\N
5	some title	15.2200003	\N	\N
6	some title	123	\N	\N
7	some title	15.2200003	\N	\N
8	some title	15.2200003	\N	\N
9	Test	10	{"test":"test","new test":"new test"}	\N
10	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
11	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
12	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
13	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
14	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
15	some title	15.2200003	null	null
16	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
17	some title	15.2200003	null	null
18	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
19	some title	15.2200003	null	null
20	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
21	some title	15.2200003	null	null
22	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
23	some title	15.2200003	null	null
24	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
25	some title	15.2200003	null	null
26	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
27	some title	15.2200003	null	null
28	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
29	some title	15.2200003	null	null
30	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
31	some title	15.2200003	null	null
32	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
33	some title	15.2200003	null	null
34	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
35	some title	15.2200003	null	null
36	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
57	some title	15.2200003	null	null
37	some title	15.2200003	null	null
38	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
39	some title	15.2200003	null	null
40	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
41	some title	15.2200003	null	null
42	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
43	some title	15.2200003	null	null
44	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
45	some title	15.2200003	null	null
46	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
47	some title	15.2200003	null	null
48	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
49	some title	15.2200003	null	null
50	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
51	some title	15.2200003	null	null
52	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
53	some title	15.2200003	null	null
54	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
58	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
59	some title	15.2200003	null	null
60	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
61	some title	15.2200003	null	null
62	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
63	some title	15.2200003	null	null
64	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
65	some title	15.2200003	null	null
66	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
67	some title	15.2200003	null	null
68	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
69	some title	15.2200003	null	null
70	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
71	some title	15.2200003	null	null
72	Test	10	{"test":"test","new test":"new test"}	{"binary test": "binary test", "new binary test": "new binary test"}
73	some title	15.2200003	null	null
\.


--
-- Name: books_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.books_id_seq', 73, true);


--
-- Data for Name: books_theme; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.books_theme (theme_id, book_id) FROM stdin;
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
1	5
1	7
1	8
1	15
1	17
1	19
1	21
1	23
1	25
1	27
1	29
1	31
1	33
1	35
1	37
1	39
1	41
1	43
1	45
1	47
1	49
1	51
1	53
1	55
1	57
1	59
1	61
1	63
1	65
1	67
1	69
1	71
1	73
\.


--
-- Data for Name: categorization; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categorization (post_id, category_id) FROM stdin;
528	1
713	5
1	22
529	1
599	1
599	2
1	75
1	23
529	2
1	76
1	1
1	2
2	2
2	1
681	26
681	27
1	77
612	1
612	4
615	1
615	4
618	1
618	4
621	1
621	4
9	5
624	1
624	5
628	1
628	7
1	8
631	1
631	8
1	10
1	11
1	12
708	44
708	45
1	13
10	1
10	2
708	46
708	47
708	48
1	14
708	49
708	50
708	51
1	15
1	61
1	16
1	62
1	17
1	63
1	18
1	19
1	64
1	20
1	65
1	21
1	66
1	67
1	68
1	69
1	70
1	71
1	72
1	73
1	74
\.


--
-- Data for Name: category; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.category (id, title) FROM stdin;
1	test category
2	new category
3	new category 2
4	new category 4
5	test new
6	test new
7	test new
8	test new
9	test new
10	test new
11	test new
12	test new
13	test new
14	test new
15	test new
16	test new
17	test new
18	test new
19	test new
20	test new
21	test new
22	test new
23	test new
24	test new
25	test new
26	test new
27	test new
28	test new
29	test new
30	test new
31	test new
32	test new
33	test new
34	test new
35	test new
36	test new
37	test new
38	test new
39	test new
40	test new
41	test new
42	test new
43	test new
44	test new
45	test new
46	test new
47	test new
48	test new
49	test new
50	test new
51	test new
52	test new
53	test new
54	test new
55	test new
56	test new
57	test new
58	test new
59	test new
60	test new
61	test new
62	test new
63	test new
64	test new
65	test new
66	test new
67	test new
68	test new
69	test new
70	test new
71	test new
72	test new
73	test new
74	test new
75	test new
76	test new
77	test new
\.


--
-- Name: category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.category_id_seq', 77, true);


--
-- Data for Name: post; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.post (id, author_id, editor_id, is_published, date_published, post_title) FROM stdin;
713	1	1	f	2018-11-21 15:05:30	New post
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
75	5	5	f	2018-10-31 14:40:56	New post
76	1	1	f	2018-10-31 14:40:56	New post
201	5	5	f	2018-11-08 12:26:30	New post
78	5	5	f	2018-10-31 14:40:58	New post
79	1	1	f	2018-10-31 14:40:58	New post
111	5	5	f	2018-11-08 12:12:41	New post
112	1	1	f	2018-11-08 12:12:41	New post
81	5	5	f	2018-10-31 14:41:00	New post
82	1	1	f	2018-10-31 14:41:00	New post
123	5	5	f	2018-11-08 12:23:46	New post
124	1	1	f	2018-11-08 12:23:46	New post
84	5	5	f	2018-10-31 14:41:53	New post
85	1	1	f	2018-10-31 14:41:53	New post
114	5	5	f	2018-11-08 12:14:42	New post
115	1	1	f	2018-11-08 12:14:42	New post
87	5	5	f	2018-11-08 09:17:21	New post
88	1	1	f	2018-11-08 09:17:21	New post
102	5	5	f	2018-11-08 12:11:48	New post
103	1	1	f	2018-11-08 12:11:48	New post
90	5	5	f	2018-11-08 09:18:25	New post
91	1	1	f	2018-11-08 09:18:25	New post
135	5	5	f	2018-11-08 12:24:02	New post
136	1	1	f	2018-11-08 12:24:02	New post
93	5	5	f	2018-11-08 09:19:00	New post
94	1	1	f	2018-11-08 09:19:00	New post
105	5	5	f	2018-11-08 12:11:49	New post
106	1	1	f	2018-11-08 12:11:49	New post
96	5	5	f	2018-11-08 09:19:14	New post
97	1	1	f	2018-11-08 09:19:14	New post
117	5	5	f	2018-11-08 12:22:35	New post
118	1	1	f	2018-11-08 12:22:35	New post
126	5	5	f	2018-11-08 12:23:47	New post
108	5	5	f	2018-11-08 12:11:50	New post
109	1	1	f	2018-11-08 12:11:50	New post
127	1	1	f	2018-11-08 12:23:47	New post
132	5	5	f	2018-11-08 12:24:01	New post
120	5	5	f	2018-11-08 12:22:37	New post
121	1	1	f	2018-11-08 12:22:37	New post
133	1	1	f	2018-11-08 12:24:01	New post
139	1	1	f	2018-11-08 12:25:17	New post
129	5	5	f	2018-11-08 12:24:00	New post
130	1	1	f	2018-11-08 12:24:00	New post
141	5	5	f	2018-11-08 12:25:18	New post
142	1	1	f	2018-11-08 12:25:18	New post
138	5	5	f	2018-11-08 12:25:17	New post
145	1	1	f	2018-11-08 12:25:19	New post
144	5	5	f	2018-11-08 12:25:18	New post
147	5	5	f	2018-11-08 12:25:19	New post
148	1	1	f	2018-11-08 12:25:19	New post
150	5	5	f	2018-11-08 12:25:20	New post
151	1	1	f	2018-11-08 12:25:20	New post
153	5	5	f	2018-11-08 12:25:20	New post
154	1	1	f	2018-11-08 12:25:20	New post
156	5	5	f	2018-11-08 12:25:21	New post
157	1	1	f	2018-11-08 12:25:21	New post
159	5	5	f	2018-11-08 12:25:22	New post
160	1	1	f	2018-11-08 12:25:22	New post
202	1	1	f	2018-11-08 12:26:30	New post
243	5	5	f	2018-11-08 12:26:58	New post
162	5	5	f	2018-11-08 12:25:22	New post
163	1	1	f	2018-11-08 12:25:22	New post
244	1	1	f	2018-11-08 12:26:58	New post
204	5	5	f	2018-11-08 12:26:31	New post
205	1	1	f	2018-11-08 12:26:31	New post
165	5	5	f	2018-11-08 12:25:23	New post
166	1	1	f	2018-11-08 12:25:23	New post
270	5	5	f	2018-11-08 12:27:17	New post
271	1	1	f	2018-11-08 12:27:17	New post
168	5	5	f	2018-11-08 12:25:23	New post
169	1	1	f	2018-11-08 12:25:23	New post
207	5	5	f	2018-11-08 12:26:32	New post
208	1	1	f	2018-11-08 12:26:32	New post
246	5	5	f	2018-11-08 12:27:08	New post
171	5	5	f	2018-11-08 12:25:24	New post
172	1	1	f	2018-11-08 12:25:24	New post
247	1	1	f	2018-11-08 12:27:08	New post
210	5	5	f	2018-11-08 12:26:33	New post
211	1	1	f	2018-11-08 12:26:33	New post
174	5	5	f	2018-11-08 12:25:25	New post
175	1	1	f	2018-11-08 12:25:25	New post
307	1	1	f	2018-11-08 12:27:42	New post
288	5	5	f	2018-11-08 12:27:38	New post
289	1	1	f	2018-11-08 12:27:38	New post
177	5	5	f	2018-11-08 12:25:26	New post
178	1	1	f	2018-11-08 12:25:26	New post
213	5	5	f	2018-11-08 12:26:34	New post
214	1	1	f	2018-11-08 12:26:34	New post
249	5	5	f	2018-11-08 12:27:11	New post
180	5	5	f	2018-11-08 12:25:27	New post
181	1	1	f	2018-11-08 12:25:27	New post
250	1	1	f	2018-11-08 12:27:11	New post
216	5	5	f	2018-11-08 12:26:34	New post
217	1	1	f	2018-11-08 12:26:34	New post
183	5	5	f	2018-11-08 12:25:28	New post
184	1	1	f	2018-11-08 12:25:28	New post
273	5	5	f	2018-11-08 12:27:18	New post
274	1	1	f	2018-11-08 12:27:18	New post
186	5	5	f	2018-11-08 12:25:31	New post
187	1	1	f	2018-11-08 12:25:31	New post
219	5	5	f	2018-11-08 12:26:35	New post
220	1	1	f	2018-11-08 12:26:35	New post
252	5	5	f	2018-11-08 12:27:13	New post
189	5	5	f	2018-11-08 12:25:31	New post
190	1	1	f	2018-11-08 12:25:31	New post
253	1	1	f	2018-11-08 12:27:13	New post
222	5	5	f	2018-11-08 12:26:36	New post
223	1	1	f	2018-11-08 12:26:36	New post
192	5	5	f	2018-11-08 12:25:33	New post
193	1	1	f	2018-11-08 12:25:33	New post
300	5	5	f	2018-11-08 12:27:40	New post
195	5	5	f	2018-11-08 12:25:34	New post
196	1	1	f	2018-11-08 12:25:34	New post
225	5	5	f	2018-11-08 12:26:42	New post
226	1	1	f	2018-11-08 12:26:42	New post
255	5	5	f	2018-11-08 12:27:14	New post
198	5	5	f	2018-11-08 12:25:35	New post
199	1	1	f	2018-11-08 12:25:35	New post
256	1	1	f	2018-11-08 12:27:14	New post
228	5	5	f	2018-11-08 12:26:53	New post
229	1	1	f	2018-11-08 12:26:53	New post
276	5	5	f	2018-11-08 12:27:18	New post
277	1	1	f	2018-11-08 12:27:18	New post
231	5	5	f	2018-11-08 12:26:54	New post
232	1	1	f	2018-11-08 12:26:54	New post
258	5	5	f	2018-11-08 12:27:14	New post
259	1	1	f	2018-11-08 12:27:14	New post
234	5	5	f	2018-11-08 12:26:55	New post
235	1	1	f	2018-11-08 12:26:55	New post
301	1	1	f	2018-11-08 12:27:40	New post
291	5	5	f	2018-11-08 12:27:38	New post
292	1	1	f	2018-11-08 12:27:38	New post
237	5	5	f	2018-11-08 12:26:56	New post
238	1	1	f	2018-11-08 12:26:56	New post
261	5	5	f	2018-11-08 12:27:15	New post
262	1	1	f	2018-11-08 12:27:15	New post
240	5	5	f	2018-11-08 12:26:57	New post
241	1	1	f	2018-11-08 12:26:57	New post
279	5	5	f	2018-11-08 12:27:19	New post
280	1	1	f	2018-11-08 12:27:19	New post
264	5	5	f	2018-11-08 12:27:16	New post
265	1	1	f	2018-11-08 12:27:16	New post
319	1	1	f	2018-11-08 12:32:13	New post
282	5	5	f	2018-11-08 12:27:19	New post
267	5	5	f	2018-11-08 12:27:16	New post
268	1	1	f	2018-11-08 12:27:16	New post
283	1	1	f	2018-11-08 12:27:19	New post
312	5	5	f	2018-11-08 12:32:11	New post
294	5	5	f	2018-11-08 12:27:39	New post
295	1	1	f	2018-11-08 12:27:39	New post
285	5	5	f	2018-11-08 12:27:21	New post
286	1	1	f	2018-11-08 12:27:21	New post
313	1	1	f	2018-11-08 12:32:11	New post
303	5	5	f	2018-11-08 12:27:41	New post
304	1	1	f	2018-11-08 12:27:41	New post
297	5	5	f	2018-11-08 12:27:40	New post
298	1	1	f	2018-11-08 12:27:40	New post
309	5	5	f	2018-11-08 12:31:23	New post
310	1	1	f	2018-11-08 12:31:23	New post
306	5	5	f	2018-11-08 12:27:42	New post
315	5	5	f	2018-11-08 12:32:12	New post
316	1	1	f	2018-11-08 12:32:12	New post
318	5	5	f	2018-11-08 12:32:13	New post
321	5	5	f	2018-11-08 12:32:14	New post
322	1	1	f	2018-11-08 12:32:14	New post
324	5	5	f	2018-11-08 12:32:14	New post
325	1	1	f	2018-11-08 12:32:14	New post
327	5	5	f	2018-11-08 12:32:16	New post
328	1	1	f	2018-11-08 12:32:16	New post
330	5	5	f	2018-11-08 12:32:55	New post
331	1	1	f	2018-11-08 12:32:55	New post
333	5	5	f	2018-11-08 12:32:56	New post
334	1	1	f	2018-11-08 12:32:56	New post
336	5	5	f	2018-11-08 12:34:43	New post
337	1	1	f	2018-11-08 12:34:43	New post
378	5	5	f	2018-11-08 15:32:51	New post
379	1	1	f	2018-11-08 15:32:51	New post
339	5	5	f	2018-11-08 12:35:31	New post
340	1	1	f	2018-11-08 12:35:31	New post
420	5	5	f	2018-11-08 15:35:30	New post
421	1	1	f	2018-11-08 15:35:30	New post
381	5	5	f	2018-11-08 15:32:53	New post
382	1	1	f	2018-11-08 15:32:53	New post
342	5	5	f	2018-11-08 12:35:32	New post
343	1	1	f	2018-11-08 12:35:32	New post
475	1	1	f	2018-11-08 15:37:55	New post
447	5	5	f	2018-11-08 15:35:38	New post
345	5	5	f	2018-11-08 12:35:37	New post
346	1	1	f	2018-11-08 12:35:37	New post
384	5	5	f	2018-11-08 15:35:20	New post
385	1	1	f	2018-11-08 15:35:20	New post
448	1	1	f	2018-11-08 15:35:38	New post
348	5	5	f	2018-11-08 12:35:45	New post
349	1	1	f	2018-11-08 12:35:45	New post
423	5	5	f	2018-11-08 15:35:31	New post
424	1	1	f	2018-11-08 15:35:31	New post
387	5	5	f	2018-11-08 15:35:21	New post
388	1	1	f	2018-11-08 15:35:21	New post
351	5	5	f	2018-11-08 14:53:54	New post
352	1	1	f	2018-11-08 14:53:54	New post
354	5	5	f	2018-11-08 15:30:38	New post
355	1	1	f	2018-11-08 15:30:38	New post
390	5	5	f	2018-11-08 15:35:22	New post
391	1	1	f	2018-11-08 15:35:22	New post
357	5	5	f	2018-11-08 15:30:40	New post
358	1	1	f	2018-11-08 15:30:40	New post
426	5	5	f	2018-11-08 15:35:32	New post
427	1	1	f	2018-11-08 15:35:32	New post
393	5	5	f	2018-11-08 15:35:22	New post
394	1	1	f	2018-11-08 15:35:22	New post
360	5	5	f	2018-11-08 15:30:41	New post
361	1	1	f	2018-11-08 15:30:41	New post
465	5	5	f	2018-11-08 15:37:53	New post
450	5	5	f	2018-11-08 15:35:40	New post
363	5	5	f	2018-11-08 15:30:41	New post
364	1	1	f	2018-11-08 15:30:41	New post
396	5	5	f	2018-11-08 15:35:23	New post
397	1	1	f	2018-11-08 15:35:23	New post
451	1	1	f	2018-11-08 15:35:40	New post
366	5	5	f	2018-11-08 15:30:42	New post
367	1	1	f	2018-11-08 15:30:42	New post
429	5	5	f	2018-11-08 15:35:33	New post
430	1	1	f	2018-11-08 15:35:33	New post
399	5	5	f	2018-11-08 15:35:24	New post
400	1	1	f	2018-11-08 15:35:24	New post
369	5	5	f	2018-11-08 15:30:43	New post
370	1	1	f	2018-11-08 15:30:43	New post
466	1	1	f	2018-11-08 15:37:53	New post
372	5	5	f	2018-11-08 15:30:45	New post
373	1	1	f	2018-11-08 15:30:45	New post
402	5	5	f	2018-11-08 15:35:25	New post
403	1	1	f	2018-11-08 15:35:25	New post
375	5	5	f	2018-11-08 15:30:46	New post
376	1	1	f	2018-11-08 15:30:46	New post
432	5	5	f	2018-11-08 15:35:34	New post
433	1	1	f	2018-11-08 15:35:34	New post
405	5	5	f	2018-11-08 15:35:26	New post
406	1	1	f	2018-11-08 15:35:26	New post
483	5	5	f	2018-11-08 15:38:00	New post
453	5	5	f	2018-11-08 15:35:41	New post
408	5	5	f	2018-11-08 15:35:27	New post
409	1	1	f	2018-11-08 15:35:27	New post
454	1	1	f	2018-11-08 15:35:41	New post
435	5	5	f	2018-11-08 15:35:35	New post
436	1	1	f	2018-11-08 15:35:35	New post
411	5	5	f	2018-11-08 15:35:28	New post
412	1	1	f	2018-11-08 15:35:28	New post
484	1	1	f	2018-11-08 15:38:00	New post
414	5	5	f	2018-11-08 15:35:28	New post
415	1	1	f	2018-11-08 15:35:28	New post
477	5	5	f	2018-11-08 15:37:59	New post
438	5	5	f	2018-11-08 15:35:36	New post
439	1	1	f	2018-11-08 15:35:36	New post
417	5	5	f	2018-11-08 15:35:29	New post
418	1	1	f	2018-11-08 15:35:29	New post
468	5	5	f	2018-11-08 15:37:54	New post
456	5	5	f	2018-11-08 15:35:42	New post
457	1	1	f	2018-11-08 15:35:42	New post
441	5	5	f	2018-11-08 15:35:36	New post
442	1	1	f	2018-11-08 15:35:36	New post
469	1	1	f	2018-11-08 15:37:54	New post
478	1	1	f	2018-11-08 15:37:59	New post
444	5	5	f	2018-11-08 15:35:37	New post
445	1	1	f	2018-11-08 15:35:37	New post
459	5	5	f	2018-11-08 15:35:43	New post
460	1	1	f	2018-11-08 15:35:43	New post
471	5	5	f	2018-11-08 15:37:55	New post
472	1	1	f	2018-11-08 15:37:55	New post
462	5	5	f	2018-11-08 15:37:52	New post
463	1	1	f	2018-11-08 15:37:52	New post
492	5	5	f	2018-11-08 15:38:03	New post
480	5	5	f	2018-11-08 15:38:00	New post
474	5	5	f	2018-11-08 15:37:55	New post
481	1	1	f	2018-11-08 15:38:00	New post
493	1	1	f	2018-11-08 15:38:03	New post
489	5	5	f	2018-11-08 15:38:02	New post
486	5	5	f	2018-11-08 15:38:01	New post
487	1	1	f	2018-11-08 15:38:01	New post
490	1	1	f	2018-11-08 15:38:02	New post
496	1	1	f	2018-11-08 15:38:04	New post
495	5	5	f	2018-11-08 15:38:03	New post
498	5	5	f	2018-11-08 15:39:12	New post
499	1	1	f	2018-11-08 15:39:12	New post
501	5	5	f	2018-11-08 15:39:14	New post
502	1	1	f	2018-11-08 15:39:14	New post
504	5	5	f	2018-11-08 15:39:15	New post
505	1	1	f	2018-11-08 15:39:15	New post
507	5	5	f	2018-11-08 15:39:16	New post
508	1	1	f	2018-11-08 15:39:16	New post
510	5	5	f	2018-11-08 15:39:17	New post
511	1	1	f	2018-11-08 15:39:17	New post
583	5	5	f	2018-11-12 16:18:35	New post
584	1	1	f	2018-11-12 16:18:35	New post
513	5	5	f	2018-11-08 15:39:17	New post
514	1	1	f	2018-11-08 15:39:17	New post
612	5	5	t	2018-11-12 16:28:04	some title
613	5	5	f	2018-11-12 16:28:43	New post
516	5	5	f	2018-11-08 15:39:18	New post
517	1	1	f	2018-11-08 15:39:18	New post
614	1	1	f	2018-11-12 16:28:43	New post
586	5	5	f	2018-11-12 16:19:54	New post
587	1	1	f	2018-11-12 16:19:55	New post
519	5	5	f	2018-11-08 15:39:19	New post
520	1	1	f	2018-11-08 15:39:19	New post
635	1	1	f	2018-11-12 16:38:31	New post
522	5	5	f	2018-11-08 15:40:00	New post
523	1	1	f	2018-11-08 15:40:00	New post
615	5	5	t	2018-11-12 16:28:43	some title
589	5	5	f	2018-11-12 16:19:56	New post
590	1	1	f	2018-11-12 16:19:56	New post
525	5	5	f	2018-11-08 15:40:01	New post
526	1	1	f	2018-11-08 15:40:01	New post
616	5	5	f	2018-11-12 16:29:03	New post
617	1	1	f	2018-11-12 16:29:03	New post
528	5	5	f	2018-11-08 15:40:02	New post
529	1	1	f	2018-11-08 15:40:02	New post
592	5	5	f	2018-11-12 16:19:56	New post
593	1	1	f	2018-11-12 16:19:56	New post
531	5	5	f	2018-11-08 15:40:03	New post
532	1	1	f	2018-11-08 15:40:03	New post
636	1	1	f	2018-11-12 16:38:31	New post
618	5	5	t	2018-11-12 16:29:03	some title
534	5	5	f	2018-11-08 15:40:03	New post
535	1	1	f	2018-11-08 15:40:03	New post
619	5	5	f	2018-11-12 16:33:01	New post
595	5	5	f	2018-11-12 16:19:57	New post
596	1	1	f	2018-11-12 16:19:57	New post
537	5	5	f	2018-11-08 15:40:04	New post
538	1	1	f	2018-11-08 15:40:04	New post
620	1	1	f	2018-11-12 16:33:02	New post
540	5	5	f	2018-11-08 15:40:05	New post
541	1	1	f	2018-11-08 15:40:05	New post
598	5	5	f	2018-11-12 16:21:12	New post
543	5	5	f	2018-11-08 15:54:53	New post
544	1	1	f	2018-11-08 15:54:53	New post
621	5	5	t	2018-11-12 16:33:03	some title
9	1	1	f	2018-05-25 14:08:11	New post
546	5	5	f	2018-11-08 15:54:54	New post
547	1	1	f	2018-11-08 15:54:54	New post
622	5	5	f	2018-11-12 16:34:09	New post
562	5	5	f	2018-11-12 15:47:37	New post
568	5	5	f	2018-11-12 16:16:18	New post
549	5	5	f	2018-11-12 10:44:37	New post
550	1	1	f	2018-11-12 10:44:37	New post
569	1	1	f	2018-11-12 16:16:18	New post
623	1	1	f	2018-11-12 16:34:09	New post
552	5	5	f	2018-11-12 11:48:05	New post
599	1	1	f	2018-11-12 16:21:12	New post
601	5	5	f	2018-11-12 16:21:59	New post
571	5	5	f	2018-11-12 16:17:26	New post
572	1	1	f	2018-11-12 16:17:26	New post
602	1	1	f	2018-11-12 16:21:59	New post
553	1	1	f	2018-11-12 11:48:05	New post
555	5	5	t	2018-11-12 15:47:10	some title
556	5	5	f	2018-11-12 15:47:21	New post
557	1	1	f	2018-11-12 15:47:21	New post
558	5	5	t	2018-11-12 15:47:21	some title
559	5	5	f	2018-11-12 15:47:28	New post
560	1	1	f	2018-11-12 15:47:28	New post
561	5	5	t	2018-11-12 15:47:28	some title
645	1	1	f	2018-11-12 16:40:26	New post
574	5	5	f	2018-11-12 16:17:56	New post
575	1	1	f	2018-11-12 16:17:56	New post
624	5	5	t	2018-11-12 16:34:09	some title
604	5	5	f	2018-11-12 16:22:24	New post
605	1	1	f	2018-11-12 16:22:24	New post
577	5	5	f	2018-11-12 16:17:58	New post
578	1	1	f	2018-11-12 16:17:58	New post
625	1	1	f	2018-11-12 16:35:26	New post
580	5	5	f	2018-11-12 16:18:33	New post
581	1	1	f	2018-11-12 16:18:33	New post
626	1	1	f	2018-11-12 16:36:04	New post
607	5	5	f	2018-11-12 16:23:39	New post
627	1	1	f	2018-11-12 16:36:04	New post
608	1	1	f	2018-11-12 16:23:39	New post
628	1	1	t	2018-11-12 16:36:04	some title
610	5	5	f	2018-11-12 16:28:04	New post
611	1	1	f	2018-11-12 16:28:04	New post
638	1	1	f	2018-11-12 16:38:32	New post
629	1	1	f	2018-11-12 16:36:12	New post
630	1	1	f	2018-11-12 16:36:12	New post
639	1	1	f	2018-11-12 16:38:32	New post
631	1	1	t	2018-11-12 16:36:12	some title
632	1	1	f	2018-11-12 16:38:06	New post
633	1	1	f	2018-11-12 16:38:06	New post
650	1	1	f	2018-11-12 16:40:39	New post
651	1	1	f	2018-11-12 16:40:39	New post
647	1	1	f	2018-11-12 16:40:36	New post
641	1	1	f	2018-11-12 16:38:41	New post
642	1	1	f	2018-11-12 16:38:41	New post
648	1	1	f	2018-11-12 16:40:36	New post
644	1	1	f	2018-11-12 16:40:26	New post
653	1	1	f	2018-11-12 16:42:05	New post
654	1	1	f	2018-11-12 16:42:05	New post
656	1	1	f	2018-11-12 16:42:06	New post
657	1	1	f	2018-11-12 16:42:06	New post
659	1	1	f	2018-11-15 12:50:42	New post
660	1	1	f	2018-11-15 12:50:42	New post
662	1	1	f	2018-11-15 12:59:50	New post
663	1	1	f	2018-11-15 12:59:50	New post
665	1	1	f	2018-11-15 12:59:53	New post
666	1	1	f	2018-11-15 12:59:53	New post
668	1	1	f	2018-11-16 10:29:20	New post
669	1	1	f	2018-11-16 10:29:20	New post
671	1	1	f	2018-11-16 10:29:21	New post
672	1	1	f	2018-11-16 10:29:21	New post
711	\N	1	f	2018-11-21 13:54:32	New post
674	1	1	f	2018-11-21 13:32:16	New post
675	1	1	f	2018-11-21 13:32:16	New post
714	\N	1	f	2018-11-21 15:05:30	New post
716	1	1	f	2018-11-21 15:08:23	New post
677	1	1	f	2018-11-21 13:33:10	New post
678	1	1	f	2018-11-21 13:33:10	New post
717	1	1	f	2018-11-21 15:08:23	New post
680	1	1	f	2018-11-21 13:33:11	New post
719	1	1	f	2018-11-21 15:30:13	New post
681	1	1	f	2018-11-21 13:33:11	New post
683	1	1	f	2018-11-21 13:36:46	New post
684	1	1	f	2018-11-21 13:36:46	New post
747	354	1	f	2018-11-21 15:33:03	New post
686	1	1	f	2018-11-21 13:37:10	New post
687	1	1	f	2018-11-21 13:37:10	New post
749	1	1	f	2018-11-21 15:33:04	New post
720	\N	1	f	2018-11-21 15:30:13	New post
723	1	1	f	2018-11-21 15:30:42	New post
689	1	1	f	2018-11-21 13:37:18	New post
690	1	1	f	2018-11-21 13:37:18	New post
692	1	1	f	2018-11-21 13:42:45	New post
693	1	1	f	2018-11-21 13:42:45	New post
724	\N	1	f	2018-11-21 15:30:42	New post
695	1	1	f	2018-11-21 13:42:47	New post
696	1	1	f	2018-11-21 13:42:47	New post
726	\N	\N	f	2018-11-21 15:30:43	test title
727	1	1	f	2018-11-21 15:30:50	New post
698	1	1	f	2018-11-21 13:42:55	New post
699	1	1	f	2018-11-21 13:42:55	New post
701	1	1	f	2018-11-21 13:43:46	New post
702	1	1	f	2018-11-21 13:43:46	New post
728	\N	1	f	2018-11-21 15:30:50	New post
704	1	1	f	2018-11-21 13:43:54	New post
705	1	1	f	2018-11-21 13:43:54	New post
730	\N	\N	f	2018-11-21 15:30:50	test title
731	1	1	f	2018-11-21 15:32:44	New post
707	1	1	f	2018-11-21 13:43:56	New post
750	356	1	f	2018-11-21 15:33:04	New post
732	346	1	f	2018-11-21 15:32:45	New post
734	346	\N	f	2018-11-21 15:32:45	test title
708	1	1	f	2018-11-21 13:43:56	New post
710	1	1	f	2018-11-21 13:54:32	New post
735	1	1	f	2018-11-21 15:32:46	New post
752	1	1	f	2018-11-21 15:33:39	New post
736	348	1	f	2018-11-21 15:32:46	New post
738	348	\N	f	2018-11-21 15:32:46	test title
739	1	1	f	2018-11-21 15:32:47	New post
740	350	1	f	2018-11-21 15:32:47	New post
742	350	\N	f	2018-11-21 15:32:47	test title
743	1	1	f	2018-11-21 15:33:02	New post
744	352	1	f	2018-11-21 15:33:02	New post
746	1	1	f	2018-11-21 15:33:03	New post
753	358	1	f	2018-11-21 15:33:39	New post
755	1	1	f	2018-11-21 15:40:27	New post
756	360	1	f	2018-11-21 15:40:27	New post
758	1	1	f	2018-11-21 15:40:28	New post
759	362	1	f	2018-11-21 15:40:28	New post
761	1	1	f	2018-11-21 15:40:29	New post
1	5	5	f	2018-05-25 14:06:23	New post
762	364	1	f	2018-11-21 15:40:29	New post
\.


--
-- Name: post_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.post_id_seq', 763, true);


--
-- Data for Name: theme; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.theme (id, title) FROM stdin;
1	test theme
\.


--
-- Name: theme_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.theme_id_seq', 1, true);


--
-- Data for Name: user_profile; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_profile (id, user_id, first_name, last_name) FROM stdin;
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
32	5	test	test
33	5	test	test
34	5	test	test
35	5	test	test
36	5	test	test
37	5	test	test
38	5	test	test
39	5	test	test
40	5	test	test
41	5	test	test
42	5	test	test
43	5	test	test
44	5	test	test
45	5	test	test
46	5	test	test
47	5	test	test
48	5	test	test
49	5	test	test
50	5	test	test
51	5	test	test
52	5	test	test
53	5	test	test
54	5	test	test
55	5	test	test
56	5	test	test
57	5	test	test
58	5	test	test
59	5	test	test
60	5	test	test
61	5	test	test
62	5	test	test
63	5	test	test
64	5	test	test
65	5	test	test
66	5	test	test
67	5	test	test
68	5	test	test
69	5	test	test
70	5	test	test
71	5	test	test
72	5	test	test
73	5	test	test
74	5	test	test
75	5	test	test
76	5	test	test
77	5	test	test
78	5	test	test
79	5	test	test
80	5	test	test
81	5	test	test
82	5	test	test
83	5	test	test
84	5	test	test
85	5	test	test
86	5	test	test
87	5	test	test
88	5	test	test
89	5	test	test
90	5	test	test
91	5	test	test
92	5	test	test
93	5	test	test
94	5	test	test
95	5	test	test
96	5	test	test
97	5	test	test
98	5	test	test
99	5	test	test
100	5	test	test
101	5	test	test
102	5	test	test
103	5	test	test
104	5	test	test
105	5	test	test
106	5	test	test
107	5	test	test
108	5	test	test
109	5	test	test
110	5	test	test
111	5	test	test
112	5	test	test
113	5	test	test
114	5	test	test
115	5	test	test
116	5	test	test
117	5	test	test
118	5	test	test
119	5	test	test
120	5	test	test
121	5	test	test
122	5	test	test
123	5	test	test
124	5	test	test
125	5	test	test
126	5	test	test
127	5	test	test
128	5	test	test
129	5	test	test
130	5	test	test
131	5	test	test
132	5	test	test
133	5	test	test
134	5	test	test
135	5	test	test
136	5	test	test
137	5	test	test
138	5	test	test
139	5	test	test
140	5	test	test
141	5	test	test
142	5	test	test
143	5	test	test
144	5	test	test
145	5	test	test
146	5	test	test
147	5	test	test
148	5	test	test
149	5	test	test
150	5	test	test
151	5	test	test
152	5	test	test
153	5	test	test
154	5	test	test
155	5	test	test
156	5	test	test
157	5	test	test
158	5	test	test
159	5	test	test
160	5	test	test
161	5	test	test
162	5	test	test
163	5	test	test
164	5	test	test
165	5	test	test
166	5	test	test
167	5	test	test
168	5	test	test
169	5	test	test
170	5	test	test
171	5	test	test
172	5	test	test
173	5	test	test
174	5	test	test
175	5	test	test
176	5	test	test
177	5	test	test
178	5	test	test
179	5	test	test
180	5	test	test
181	5	test	test
182	5	test	test
183	5	test	test
184	5	test	test
185	5	test	test
186	5	test	test
187	5	test	test
188	5	test	test
189	5	test	test
190	5	test	test
191	5	test	test
192	5	test	test
193	5	test	test
194	5	test	test
195	5	test	test
196	5	test	test
197	5	test	test
198	5	test	test
199	5	test	test
200	5	test	test
201	5	test	test
202	5	test	test
203	5	test	test
204	5	test	test
205	5	test	test
206	5	test	test
207	5	test	test
208	5	test	test
209	5	test	test
210	5	test	test
211	5	test	test
212	5	test	test
213	5	test	test
214	1	test	test
215	1	test	test
216	1	test	test
217	1	test	test
218	1	test	test
219	1	test	test
220	1	test	test
221	1	test	test
222	1	test	test
223	1	test	test
224	1	test	test
225	1	test	test
226	1	test	test
227	1	test	test
228	1	test	test
229	1	test	test
230	1	test	test
231	1	test	test
232	1	test	test
233	1	test	test
234	1	test	test
235	1	test	test
236	1	test	test
237	1	test	test
238	1	test	test
239	1	test	test
240	1	test	test
241	1	test	test
242	1	test	test
243	1	test	test
244	1	test	test
245	1	test	test
246	1	test	test
247	1	test	test
248	1	test	test
249	1	test	test
250	1	test	test
251	1	test	test
252	1	test	test
253	1	test	test
254	1	test	test
255	1	test	test
256	1	test	test
257	1	test	test
\.


--
-- Name: user_profile_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_profile_id_seq', 257, true);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, profile_id) FROM stdin;
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
57	6a0db394cdb3b6f	\N
58	New name	\N
59	31875a94fecc0c8	\N
60	New name	\N
61	a60c0dac210ae6b	\N
62	New name	\N
63	5a62fd99fc9726d	\N
64	New name	\N
65	12d7ece2820836b	\N
66	New name	\N
67	859d015adb12024	\N
68	New name	\N
69	3e822c1df7110af	\N
70	New name	\N
71	c208cc25076392d	\N
72	New name	\N
73	8a798d3aba7c043	\N
74	New name	\N
75	Test name	\N
76	New name	\N
77	Test name	\N
78	New name	\N
79	194536c9b8d63ea	\N
80	57fa6982934a87e	\N
81	04858427f64eab1	\N
82	748279f551d1c72	\N
83	37707d50ce9cabb	\N
84	f309a3c5aaee53d	\N
85	52b4ea8ddc97927	\N
86	9d88a9f38eb9e4f	\N
87	710d0c8f7b8c34e	\N
88	3c3879ec017236b	\N
89	33ff3c9c8b907f9	\N
90	882380adb41d27d	\N
91	d5ea782870ae9f5	\N
92	d7348b29267b4a5	\N
93	f2a0f3e0528b82c	\N
94	959b9a13113a50e	\N
95	557e6a0182e1518	\N
96	67d0110dce6622d	\N
97	db1c72769762154	\N
98	763df8b98fd8236	\N
99	aad365acd660ef0	\N
100	d3da365a95049f3	\N
101	8491225857b4d8f	\N
102	f2bfd1ccf991320	\N
103	6c6188a4b8ccf0c	\N
104	de637018e11638c	\N
105	5d9a18e4e9f6e3d	\N
106	33b14713adf22f2	\N
107	b38c912d4ae25dc	\N
108	3f57050897d82ef	\N
109	30462edf25a5cad	\N
110	52b6fa427056fab	\N
111	125018a700e0c9d	\N
112	f8cbd022c3b982d	\N
113	01908d69f81e58a	\N
114	3e5d78c805c84bc	\N
115	972c7007a22c2af	\N
116	b505c8161ac0cfe	\N
117	a86a791eb35fb45	\N
118	26649d555c92845	\N
119	ed54f75f1e2f568	\N
120	bdc17cd340e4cc6	\N
121	5951952ea61dddb	\N
122	7ccc488e052996c	\N
123	4f1768d3ccede7c	\N
124	1de83e47b5adb46	\N
125	d8460df2afadfac	\N
126	68059a5657b49fc	\N
127	d11a37983b7156f	\N
128	29cc52939f96484	\N
129	4e5599bc31683fe	\N
130	b27eaf4414009cf	\N
131	4afea52e9ba93e2	\N
132	b6364ce70fab6a3	\N
133	ca8cdddc1d8d96b	\N
134	79a3a68814ab4f9	\N
135	924494c4a11d761	\N
136	05df04a13bb88be	\N
137	e61faa67b43a597	\N
138	0db0eca98f0f746	\N
139	4d581dc191b0c53	\N
140	c9b8744f154523e	\N
141	f7604bca331da96	\N
142	3a01d571a32d3d1	\N
143	237f59e6f59a19d	\N
144	5d34e5408dfdd5b	\N
145	dc37c0cff8c6db1	\N
146	aa55a6eafb0b18d	\N
147	ae284afdbcd6b47	\N
148	078b11e5cb5e208	\N
149	a0ac160194ded0a	\N
150	6d3986204b41b38	\N
151	88166aa3d3f47fa	\N
152	b3da5e023b43d33	\N
153	1aba732457ed6e5	\N
154	a70a8932ac1e4fc	\N
155	c3e832c71b4a59b	\N
156	a3662e9fc54045e	\N
5	New name	2
157	2f1450d5dce290e	\N
158	1befb75fe785702	\N
159	e311d086092a20d	\N
160	e7b0cfd4efb80f9	\N
161	4a840f86740a6f2	\N
162	663176db15102fb	\N
163	0887b92031711b8	\N
164	53766ec6116c714	\N
165	2ea166d57077292	\N
166	0ac38348ad01266	\N
167	22ccd34b365de21	\N
168	16f415356bd59fe	\N
169	c086885c842c1e6	\N
170	c1c49e0af260666	\N
171	bff92857bea7f10	\N
172	13d0387e5444cab	\N
173	c273da8c4ed5e74	\N
174	142a1c5d9041788	\N
175	a2aa85e1a1b9c77	\N
176	0b7dec2a473f114	\N
177	4ee5191dae9d1df	\N
178	5159630a74e84cd	\N
179	f2797eb8519c7a1	\N
180	4c4ad43987d350d	\N
181	f6541e66842e75c	\N
182	c2ee9162181c37b	\N
183	96a44cb3c52d9be	\N
184	131aab38b274c29	\N
185	e6e06eedaa56cd6	\N
186	e3ac9f566208931	\N
187	87553440669c615	\N
188	f137cd4ab1984c8	\N
189	f6f17821fb83c82	\N
190	aa94a290d104cbe	\N
191	e77f646004f15da	\N
192	5af622d6fbea263	\N
193	6a2976a05a27063	\N
194	daf05c0b53204f8	\N
195	8c276daa4ec0557	\N
196	ef1a1f0c09aba46	\N
197	41d7ca91e0870c6	\N
198	e297334ef09328e	\N
199	d04adb24390552f	\N
200	faad04c5abf721a	\N
201	d13addf64a2bb16	\N
202	544c866816498a8	\N
203	7619e22ad31acdb	\N
204	d4cc58e5f711d29	\N
205	98557574dffd3ee	\N
206	45f758d5e942e60	\N
207	96caafa46b01b21	\N
208	1b774fc13331aee	\N
209	900ffaf098a1cdc	\N
210	474e332a6142d9c	\N
211	a97625bdb18591e	\N
212	4343aff51993d77	\N
213	414a63ecc554793	\N
214	c98835ca6041859	\N
215	0b98238c004f07d	\N
216	3730a905dc30dcc	\N
217	1e5274211dc0b89	\N
218	ac0ffaeb4065568	\N
219	8710a464462444b	\N
220	61b25fb2cd209a6	\N
221	77c72c817abfa6d	\N
222	cb9d29aef8045c4	\N
223	9087483bb499e2f	\N
224	a5b54be286b79f9	\N
225	51b826d49dee117	\N
226	c05a00365e6dce7	\N
227	767dfb274871b87	\N
228	33d2722c5a027ae	\N
229	efe81f61e432774	\N
230	New name	\N
231	d7c69fc0eb7f697	\N
232	New name	\N
233	777c5f97047b1ff	\N
234	New name	\N
235	89cd25397903227	\N
236	New name	\N
237	9ae018330fbdcd6	\N
238	New name	\N
239	c218d182dd41922	\N
240	New name	\N
241	a2fb43364daf0fe	\N
242	New name	\N
243	5cfaa51e31b3a71	\N
244	New name	\N
245	026e5ba1549753b	\N
246	New name	\N
247	90e9146a84ece93	\N
248	New name	\N
249	066a2f494466960	\N
250	New name	\N
251	f07dfd85b639888	\N
252	New name	\N
253	8e9f1ee784d7b4a	\N
254	New name	\N
255	afd2b52334e2ded	\N
256	New name	\N
257	bfe071d95621ac8	\N
258	New name	\N
259	aad1ad7f62692d8	\N
260	New name	\N
261	91e37177d439ee3	\N
262	New name	\N
263	ac23125b02dd9df	\N
264	New name	\N
265	1394362341ee22d	\N
266	New name	\N
267	7842fdecc4bb807	\N
268	New name	\N
269	d524d0f83b94d53	\N
270	New name	\N
271	1ff256a4e3d65c0	\N
272	New name	\N
273	b2af1c4c8d84c81	\N
274	New name	\N
275	1ab318917ff6b69	\N
276	New name	\N
277	e507d2014c8467c	\N
278	New name	\N
279	f76de264f679517	\N
280	New name	\N
281	eb0ae0cda35745d	\N
282	New name	\N
283	d5b044a9f61e848	\N
284	New name	\N
285	fb0ba1a878f146d	\N
286	New name	\N
287	f681a56b8bacb13	\N
288	New name	\N
289	a8bea75ec2e48f5	\N
290	New name	\N
291	fc2133efc91b4ec	\N
292	New name	\N
293	151e8e244a07884	\N
294	New name	\N
295	c7ee7f1cc3667e5	\N
296	New name	\N
297	9de439adec0c5b6	\N
298	New name	\N
299	7c33ac3ed614ef6	\N
300	New name	\N
301	a72cc001b025ccd	\N
302	New name	\N
303	32d72a806201252	\N
304	New name	\N
305	675a678853d387b	\N
306	New name	\N
307	2b74534ff7ba008	\N
308	New name	\N
309	176c985bec3afe6	\N
310	New name	\N
311	382c10821c1a890	\N
312	New name	\N
313	8339ccc8092aa90	\N
314	New name	\N
315	a81f44804227586	\N
316	New name	\N
317	e76b15b85f056ab	\N
318	New name	\N
319	2a7dcaf61362293	\N
320	New name	\N
321	fd2ba8fd5ffcfef	\N
322	New name	\N
323	a4aaf14705c2916	\N
324	New name	\N
325	4442a97250d0d06	\N
326	New name	\N
327	1a87a4a4bad8736	\N
328	New name	\N
329	2a5b0e26e1bd17b	\N
330	New name	\N
331	219b8696d6fd33a	\N
332	New name	\N
333	5f98c172a9a4f20	\N
334	New name	\N
335	e13f0c07ceac4be	\N
336	New name	\N
337	629108fa9f00ac6	\N
338	New name	\N
339	4b9e21506d2e5e7	\N
340	New name	\N
341	566fffc44ef70d8	\N
342	New name	\N
343	2bb6a636485bc05	\N
344	New name	\N
345	78eeaa27aa694b8	\N
346	New name	\N
347	d69016404ec2b3d	\N
348	New name	\N
349	5c1045bb9db5fe9	\N
350	New name	\N
351	780cda7edd62847	\N
352	New name	\N
353	a8df95504d9aab4	\N
354	New name	\N
355	4d71d729161c7f5	\N
356	New name	\N
357	359aab754ceedba	\N
358	New name	\N
359	2540b5786efdb9c	\N
360	New name	\N
361	8cefca4292dc76a	\N
362	New name	\N
363	2ac47ce08430ab6	\N
364	New name	\N
\.


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 364, true);


--
-- Name: authors_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.authors
    ADD CONSTRAINT authors_pkey PRIMARY KEY (id);


--
-- Name: books_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_pkey PRIMARY KEY (id);


--
-- Name: category_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.category
    ADD CONSTRAINT category_pkey PRIMARY KEY (id);


--
-- Name: post_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.post
    ADD CONSTRAINT post_pkey PRIMARY KEY (id);


--
-- Name: theme_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.theme
    ADD CONSTRAINT theme_pkey PRIMARY KEY (id);


--
-- Name: user_profile_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.user_profile
    ADD CONSTRAINT user_profile_pkey PRIMARY KEY (id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: idx_2dfda3cb16a2b381; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_2dfda3cb16a2b381 ON public.authors_books USING btree (book_id);


--
-- Name: idx_2dfda3cbf675f31b; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_2dfda3cbf675f31b ON public.authors_books USING btree (author_id);


--
-- Name: idx_3c8753f116a2b381; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_3c8753f116a2b381 ON public.books_theme USING btree (book_id);


--
-- Name: idx_3c8753f159027487; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_3c8753f159027487 ON public.books_theme USING btree (theme_id);


--
-- Name: idx_51c812b112469de2; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_51c812b112469de2 ON public.categorization USING btree (category_id);


--
-- Name: idx_51c812b14b89032c; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_51c812b14b89032c ON public.categorization USING btree (post_id);


--
-- Name: idx_5a8a6c8d6995ac4c; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_5a8a6c8d6995ac4c ON public.post USING btree (editor_id);


--
-- Name: idx_5a8a6c8df675f31b; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_5a8a6c8df675f31b ON public.post USING btree (author_id);


--
-- Name: fk_2dfda3cb16a2b381; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.authors_books
    ADD CONSTRAINT fk_2dfda3cb16a2b381 FOREIGN KEY (book_id) REFERENCES public.books(id) ON DELETE CASCADE;


--
-- Name: fk_2dfda3cbf675f31b; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.authors_books
    ADD CONSTRAINT fk_2dfda3cbf675f31b FOREIGN KEY (author_id) REFERENCES public.books(id) ON DELETE CASCADE;


--
-- Name: fk_3c8753f116a2b381; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.books_theme
    ADD CONSTRAINT fk_3c8753f116a2b381 FOREIGN KEY (book_id) REFERENCES public.books(id) ON DELETE CASCADE;


--
-- Name: fk_3c8753f159027487; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.books_theme
    ADD CONSTRAINT fk_3c8753f159027487 FOREIGN KEY (theme_id) REFERENCES public.theme(id) ON DELETE CASCADE;


--
-- Name: fk_51c812b112469de2; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorization
    ADD CONSTRAINT fk_51c812b112469de2 FOREIGN KEY (category_id) REFERENCES public.category(id) ON DELETE CASCADE;


--
-- Name: fk_51c812b14b89032c; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorization
    ADD CONSTRAINT fk_51c812b14b89032c FOREIGN KEY (post_id) REFERENCES public.post(id) ON DELETE CASCADE;


--
-- Name: fk_5a8a6c8d6995ac4c; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.post
    ADD CONSTRAINT fk_5a8a6c8d6995ac4c FOREIGN KEY (editor_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: fk_5a8a6c8df675f31b; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.post
    ADD CONSTRAINT fk_5a8a6c8df675f31b FOREIGN KEY (author_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: fk_d95ab405a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_profile
    ADD CONSTRAINT fk_d95ab405a76ed395 FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

