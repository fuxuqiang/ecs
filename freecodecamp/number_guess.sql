--
-- PostgreSQL database dump
--

-- Dumped from database version 12.17 (Ubuntu 12.17-1.pgdg22.04+1)
-- Dumped by pg_dump version 12.17 (Ubuntu 12.17-1.pgdg22.04+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

DROP DATABASE number_guess;
--
-- Name: number_guess; Type: DATABASE; Schema: -; Owner: freecodecamp
--

CREATE DATABASE number_guess WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'C.UTF-8' LC_CTYPE = 'C.UTF-8';


ALTER DATABASE number_guess OWNER TO freecodecamp;

\connect number_guess

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: record; Type: TABLE; Schema: public; Owner: freecodecamp
--

CREATE TABLE public.record (
    username character varying(22) NOT NULL,
    times smallint NOT NULL
);


ALTER TABLE public.record OWNER TO freecodecamp;

--
-- Data for Name: record; Type: TABLE DATA; Schema: public; Owner: freecodecamp
--

INSERT INTO public.record VALUES ('user_1731212421962', 692);
INSERT INTO public.record VALUES ('user_1731212421962', 456);
INSERT INTO public.record VALUES ('user_1731212421961', 231);
INSERT INTO public.record VALUES ('user_1731212421961', 440);
INSERT INTO public.record VALUES ('user_1731212421962', 55);
INSERT INTO public.record VALUES ('user_1731212421962', 339);
INSERT INTO public.record VALUES ('user_1731212465961', 189);
INSERT INTO public.record VALUES ('user_1731212465961', 714);
INSERT INTO public.record VALUES ('user_1731212465960', 55);
INSERT INTO public.record VALUES ('user_1731212465960', 753);
INSERT INTO public.record VALUES ('user_1731212465961', 725);
INSERT INTO public.record VALUES ('user_1731212465961', 394);
INSERT INTO public.record VALUES ('user_1731212489726', 994);
INSERT INTO public.record VALUES ('user_1731212489726', 254);
INSERT INTO public.record VALUES ('user_1731212489725', 689);
INSERT INTO public.record VALUES ('user_1731212489725', 532);
INSERT INTO public.record VALUES ('user_1731212489726', 603);
INSERT INTO public.record VALUES ('user_1731212489726', 247);
INSERT INTO public.record VALUES ('user_1731212615374', 493);
INSERT INTO public.record VALUES ('user_1731212615374', 481);
INSERT INTO public.record VALUES ('user_1731212615373', 357);
INSERT INTO public.record VALUES ('user_1731212615373', 326);
INSERT INTO public.record VALUES ('user_1731212615374', 571);
INSERT INTO public.record VALUES ('user_1731212615374', 496);
INSERT INTO public.record VALUES ('user_1731212903656', 242);
INSERT INTO public.record VALUES ('user_1731212903656', 454);
INSERT INTO public.record VALUES ('user_1731212903655', 339);
INSERT INTO public.record VALUES ('user_1731212903655', 367);
INSERT INTO public.record VALUES ('user_1731212903656', 103);
INSERT INTO public.record VALUES ('user_1731212903656', 488);


--
-- PostgreSQL database dump complete
--

