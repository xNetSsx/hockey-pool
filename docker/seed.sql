--
-- PostgreSQL database dump
--

\restrict V7iNbJ1DH0WQ5PtVogSXRQXUW5l5Ev5c49alT0ujdsXGLPYvjiSdmblV4WLWVcG

-- Dumped from database version 16.13
-- Dumped by pg_dump version 16.13

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

--
-- Data for Name: team; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.team VALUES (37, 'Česká republika', 'CZE', '🇨🇿');
INSERT INTO public.team VALUES (38, 'Slovensko', 'SVK', '🇸🇰');
INSERT INTO public.team VALUES (39, 'Finsko', 'FIN', '🇫🇮');
INSERT INTO public.team VALUES (40, 'Švédsko', 'SWE', '🇸🇪');
INSERT INTO public.team VALUES (41, 'Itálie', 'ITA', '🇮🇹');
INSERT INTO public.team VALUES (42, 'Švýcarsko', 'SUI', '🇨🇭');
INSERT INTO public.team VALUES (43, 'Francie', 'FRA', '🇫🇷');
INSERT INTO public.team VALUES (44, 'Kanada', 'CAN', '🇨🇦');
INSERT INTO public.team VALUES (45, 'Lotyšsko', 'LAT', '🇱🇻');
INSERT INTO public.team VALUES (46, 'USA', 'USA', '🇺🇸');
INSERT INTO public.team VALUES (47, 'Německo', 'GER', '🇩🇪');
INSERT INTO public.team VALUES (48, 'Dánsko', 'DEN', '🇩🇰');


--
-- Data for Name: tournament; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.tournament VALUES (5, 'Olympijské hry 2026', 2026, 'oh-2026', 'finished', '2026-03-28 22:06:20');


--
-- Data for Name: match; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.match VALUES (91, 5, 38, 39, 'group_stage', '2026-02-11 16:40:00', 4, 1, true);
INSERT INTO public.match VALUES (92, 5, 40, 41, 'group_stage', '2026-02-11 21:10:00', 5, 2, true);
INSERT INTO public.match VALUES (93, 5, 42, 43, 'group_stage', '2026-02-12 12:10:00', 4, 0, true);
INSERT INTO public.match VALUES (94, 5, 37, 44, 'group_stage', '2026-02-12 16:40:00', 0, 5, true);
INSERT INTO public.match VALUES (95, 5, 45, 46, 'group_stage', '2026-02-12 21:10:00', 1, 5, true);
INSERT INTO public.match VALUES (96, 5, 47, 48, 'group_stage', '2026-02-12 21:10:00', 3, 1, true);
INSERT INTO public.match VALUES (97, 5, 39, 40, 'group_stage', '2026-02-13 12:10:00', 4, 1, true);
INSERT INTO public.match VALUES (98, 5, 41, 38, 'group_stage', '2026-02-13 12:10:00', 2, 3, true);
INSERT INTO public.match VALUES (99, 5, 43, 37, 'group_stage', '2026-02-13 16:40:00', 3, 6, true);
INSERT INTO public.match VALUES (100, 5, 44, 42, 'group_stage', '2026-02-13 21:10:00', 5, 1, true);
INSERT INTO public.match VALUES (101, 5, 40, 38, 'group_stage', '2026-02-14 12:10:00', 5, 3, true);
INSERT INTO public.match VALUES (102, 5, 47, 45, 'group_stage', '2026-02-14 12:10:00', 3, 4, true);
INSERT INTO public.match VALUES (103, 5, 39, 41, 'group_stage', '2026-02-14 16:40:00', 11, 0, true);
INSERT INTO public.match VALUES (104, 5, 46, 48, 'group_stage', '2026-02-14 21:10:00', 6, 3, true);
INSERT INTO public.match VALUES (105, 5, 42, 37, 'group_stage', '2026-02-15 12:10:00', 4, 3, true);
INSERT INTO public.match VALUES (106, 5, 44, 43, 'group_stage', '2026-02-15 16:40:00', 10, 2, true);
INSERT INTO public.match VALUES (107, 5, 48, 45, 'group_stage', '2026-02-15 19:10:00', 4, 2, true);
INSERT INTO public.match VALUES (108, 5, 46, 47, 'group_stage', '2026-02-15 21:10:00', 5, 1, true);
INSERT INTO public.match VALUES (109, 5, 42, 41, 'quarterfinal', '2026-02-17 12:10:00', 3, 0, true);
INSERT INTO public.match VALUES (110, 5, 47, 43, 'quarterfinal', '2026-02-17 12:10:00', 5, 1, true);
INSERT INTO public.match VALUES (111, 5, 37, 48, 'quarterfinal', '2026-02-17 16:40:00', 3, 2, true);
INSERT INTO public.match VALUES (112, 5, 40, 45, 'quarterfinal', '2026-02-17 21:10:00', 5, 1, true);
INSERT INTO public.match VALUES (113, 5, 38, 47, 'quarterfinal', '2026-02-18 12:10:00', 6, 2, true);
INSERT INTO public.match VALUES (114, 5, 44, 37, 'quarterfinal', '2026-02-18 14:10:00', 4, 3, true);
INSERT INTO public.match VALUES (115, 5, 39, 42, 'quarterfinal', '2026-02-18 16:40:00', 3, 2, true);
INSERT INTO public.match VALUES (116, 5, 46, 40, 'quarterfinal', '2026-02-18 21:10:00', 2, 1, true);
INSERT INTO public.match VALUES (117, 5, 44, 39, 'semifinal', '2026-02-20 16:40:00', 3, 2, true);
INSERT INTO public.match VALUES (118, 5, 46, 38, 'semifinal', '2026-02-20 21:10:00', 6, 2, true);
INSERT INTO public.match VALUES (119, 5, 38, 39, 'bronze_medal', '2026-02-21 20:40:00', 1, 6, true);
INSERT INTO public.match VALUES (120, 5, 44, 46, 'gold_medal', '2026-02-22 14:10:00', 1, 2, true);


--
-- Data for Name: special_bet_rule; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.special_bet_rule VALUES (37, 5, 46, 'Zlatá medaile', 'team', 'exact_match', 3, 1, NULL, NULL);
INSERT INTO public.special_bet_rule VALUES (38, 5, 44, 'Stříbrná medaile', 'team', 'exact_match', 3, 2, NULL, NULL);
INSERT INTO public.special_bet_rule VALUES (39, 5, 39, 'Bronzová medaile', 'team', 'exact_match', 3, 3, NULL, NULL);
INSERT INTO public.special_bet_rule VALUES (40, 5, NULL, 'Nejlepší Čech #1', 'string', 'exact_match', 2, 4, 'Pastrňák', NULL);
INSERT INTO public.special_bet_rule VALUES (41, 5, NULL, 'Nejlepší Čech #2', 'string', 'exact_match', 2, 5, 'Nečas', NULL);
INSERT INTO public.special_bet_rule VALUES (42, 5, NULL, 'Nejlepší Čech #3', 'string', 'exact_match', 2, 6, 'Červenka', NULL);
INSERT INTO public.special_bet_rule VALUES (43, 5, NULL, 'Celkem gólů ČR', 'integer', 'closest', 2, 7, NULL, 15);
INSERT INTO public.special_bet_rule VALUES (44, 5, NULL, 'Remízy v základní době', 'integer', 'closest', 2, 8, NULL, 5);
INSERT INTO public.special_bet_rule VALUES (45, 5, NULL, 'Trestné minuty Gudase', 'integer', 'closest', 2, 9, NULL, 4);


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public."user" VALUES (31, 'Ondra', NULL, '$2y$13$0mboZdt./cc6AJ4mdYQxM.vZKVmpZEaODmkCI1XaWK5eReS.BAwAu', '["ROLE_USER","ROLE_ADMIN"]');
INSERT INTO public."user" VALUES (32, 'Táda', NULL, '$2y$13$PA0ucyIu/X4H3VS78bgJG.uxT4wksx5Dvfhdp49nN/tBazHIjiJrq', '["ROLE_USER","ROLE_ADMIN"]');
INSERT INTO public."user" VALUES (33, 'Martin', NULL, '$2y$13$K/MdV3UVDQK7IBcUUpOk/.q4fpSHcvUhTa79rZSIwBVU1NPmnBNl2', '["ROLE_USER"]');
INSERT INTO public."user" VALUES (34, 'Pavel', NULL, '$2y$13$7bl2rpIfzthRjHXVcKEveOiMN/LhqanZ85j.DGGW7rK3vB.8S8zj6', '["ROLE_USER"]');
INSERT INTO public."user" VALUES (35, 'Váca', NULL, '$2y$13$4Jw6GsUj5puX5lxNZq/wtu2Lmp9L4e//eBRd/FsqnK6.hvFjWrUCi', '["ROLE_USER"]');
INSERT INTO public."user" VALUES (36, 'Kuba', NULL, '$2y$13$VFAcq9QkSB.f/59CZIdMk.DCjtWi.VvsrmBB0KSTePf9i7ogW4aPu', '["ROLE_USER"]');
INSERT INTO public."user" VALUES (37, 'Mééča', NULL, '$2y$13$hsGWqgxAFZzm4VExstSg0eA/iHRCvujJaHARupnToEFOWaN9bmkUO', '["ROLE_USER"]');
INSERT INTO public."user" VALUES (38, 'Honza S', NULL, '$2y$13$CHZyv8z40Kns0IGClZlw0uy1iFgUo875kUPV0q1aowA2KzYnQ2xYy', '["ROLE_USER","ROLE_ADMIN"]');
INSERT INTO public."user" VALUES (39, 'Mates', NULL, '$2y$13$D2UEF6Bzjd6E5SXi5cNSBe2UxKyW2IPwac82iHCi3Gp7qSeiTYtWC', '["ROLE_USER"]');
INSERT INTO public."user" VALUES (40, 'Fanda', NULL, '$2y$13$rMh6JJIMNzmqKleVzeTlE.ojy8tJPLW6pDB8/P/hGKvouDbCMbK4.', '["ROLE_USER"]');


--
-- Data for Name: point_entry; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: prediction; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.prediction VALUES (901, 31, 91, 1, 3, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (902, 32, 91, 1, 4, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (903, 33, 91, 1, 4, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (904, 34, 91, 2, 5, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (905, 35, 91, 2, 4, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (906, 36, 91, 1, 4, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (907, 37, 91, 1, 4, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (908, 38, 91, 1, 4, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (909, 39, 91, 1, 3, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (910, 40, 91, 2, 1, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (911, 31, 92, 7, 0, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (912, 32, 92, 8, 0, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (913, 33, 92, 7, 1, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (914, 34, 92, 5, 1, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (915, 35, 92, 6, 1, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (916, 36, 92, 10, 0, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (917, 37, 92, 6, 0, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (918, 38, 92, 5, 0, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (919, 39, 92, 4, 0, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (920, 40, 92, 5, 1, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (921, 31, 93, 5, 1, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (922, 32, 93, 4, 2, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (923, 33, 93, 5, 1, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (924, 34, 93, 5, 2, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (925, 35, 93, 5, 1, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (926, 36, 93, 4, 1, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (927, 37, 93, 3, 1, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (928, 38, 93, 4, 1, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (929, 39, 93, 4, 1, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (930, 40, 93, 4, 1, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (931, 31, 94, 1, 3, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (932, 32, 94, 3, 2, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (933, 33, 94, 2, 4, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (934, 34, 94, 2, 3, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (935, 35, 94, 3, 4, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (936, 36, 94, 3, 2, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (937, 37, 94, 2, 3, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (938, 38, 94, 2, 3, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (939, 39, 94, 2, 1, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (940, 40, 94, 2, 3, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (941, 31, 95, 2, 4, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (942, 32, 95, 1, 4, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (943, 33, 95, 2, 6, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (944, 34, 95, 1, 4, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (945, 35, 95, 2, 5, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (946, 36, 95, 1, 4, '2026-03-28 22:06:20', NULL);
INSERT INTO public.prediction VALUES (947, 37, 95, 2, 5, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (948, 38, 95, 1, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (949, 39, 95, 0, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (950, 40, 95, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (951, 31, 96, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (952, 32, 96, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (953, 33, 96, 4, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (954, 34, 96, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (955, 35, 96, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (956, 36, 96, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (957, 37, 96, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (958, 38, 96, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (959, 39, 96, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (960, 40, 96, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (961, 31, 97, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (962, 32, 97, 2, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (963, 33, 97, 1, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (964, 34, 97, 2, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (965, 35, 97, 1, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (966, 36, 97, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (967, 37, 97, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (968, 38, 97, 1, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (969, 39, 97, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (970, 40, 97, 5, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (971, 31, 98, 0, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (972, 32, 98, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (973, 33, 98, 1, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (974, 34, 98, 2, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (975, 35, 98, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (976, 36, 98, 0, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (977, 37, 98, 1, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (978, 38, 98, 1, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (979, 39, 98, 1, 5, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (980, 40, 98, 1, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (981, 31, 99, 2, 5, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (982, 32, 99, 1, 5, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (983, 33, 99, 1, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (984, 34, 99, 1, 7, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (985, 35, 99, 1, 6, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (986, 36, 99, 2, 5, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (987, 37, 99, 1, 5, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (988, 38, 99, 1, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (989, 39, 99, 1, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (990, 40, 99, 2, 6, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (991, 31, 100, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (992, 32, 100, 4, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (993, 33, 100, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (994, 34, 100, 5, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (995, 35, 100, 5, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (996, 36, 100, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (997, 37, 100, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (998, 38, 100, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (999, 39, 100, 4, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1000, 40, 100, 3, 5, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1001, 31, 101, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1002, 32, 101, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1003, 33, 101, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1004, 34, 101, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1005, 35, 101, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1006, 36, 101, 0, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1007, 37, 101, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1008, 38, 101, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1009, 39, 101, 2, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1010, 40, 101, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1011, 31, 102, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1012, 32, 102, 3, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1013, 33, 102, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1014, 34, 102, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1015, 35, 102, 2, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1016, 36, 102, 0, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1017, 37, 102, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1018, 38, 102, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1019, 39, 102, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1020, 40, 102, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1021, 31, 103, 5, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1022, 32, 103, 5, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1023, 33, 103, 4, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1024, 34, 103, 4, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1025, 35, 103, 5, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1026, 36, 103, 5, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1027, 37, 103, 6, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1028, 38, 103, 4, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1029, 39, 103, 3, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1030, 40, 103, 5, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1031, 31, 104, 6, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1032, 32, 104, 6, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1033, 33, 104, 5, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1034, 34, 104, 6, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1035, 35, 104, 6, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1036, 36, 104, 5, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1037, 37, 104, 4, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1038, 38, 104, 6, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1039, 39, 104, 5, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1040, 40, 104, 4, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1041, 31, 105, 1, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1042, 32, 105, 2, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1043, 33, 105, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1044, 34, 105, 2, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1045, 35, 105, 2, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1046, 36, 105, 1, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1047, 37, 105, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1048, 38, 105, 1, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1049, 39, 105, 1, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1050, 40, 105, 1, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1051, 31, 106, 6, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1052, 32, 106, 8, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1053, 33, 106, 8, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1054, 34, 106, 8, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1055, 35, 106, 8, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1056, 36, 106, 7, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1057, 37, 106, 7, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1058, 38, 106, 7, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1059, 39, 106, 10, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1060, 40, 106, 8, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1061, 31, 107, 1, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1062, 32, 107, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1063, 33, 107, 2, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1064, 34, 107, 2, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1065, 35, 107, 1, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1066, 36, 107, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1067, 37, 107, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1068, 38, 107, 1, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1069, 39, 107, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1070, 40, 107, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1071, 31, 108, 5, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1072, 32, 108, 5, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1073, 33, 108, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1074, 34, 108, 5, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1075, 35, 108, 6, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1076, 36, 108, 5, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1077, 37, 108, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1078, 38, 108, 5, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1079, 39, 108, 5, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1080, 40, 108, 7, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1081, 31, 109, 5, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1082, 32, 109, 6, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1083, 33, 109, 6, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1084, 34, 109, 6, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1085, 35, 109, 4, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1086, 36, 109, 5, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1087, 37, 109, 8, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1088, 38, 109, 6, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1089, 39, 109, 5, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1090, 40, 109, 6, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1091, 31, 110, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1092, 32, 110, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1093, 33, 110, 4, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1094, 34, 110, 5, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1095, 35, 110, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1096, 36, 110, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1097, 37, 110, 5, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1098, 38, 110, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1099, 39, 110, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1100, 40, 110, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1101, 31, 111, 5, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1102, 32, 111, 4, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1103, 33, 111, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1104, 34, 111, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1105, 35, 111, 5, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1106, 36, 111, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1107, 37, 111, 5, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1108, 38, 111, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1109, 39, 111, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1110, 40, 111, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1111, 31, 112, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1112, 32, 112, 7, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1113, 33, 112, 6, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1114, 34, 112, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1115, 35, 112, 6, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1116, 36, 112, 5, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1117, 37, 112, 6, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1118, 38, 112, 5, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1119, 39, 112, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1120, 40, 112, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1121, 31, 113, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1122, 32, 113, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1123, 33, 113, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1124, 34, 113, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1125, 35, 113, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1126, 36, 113, 3, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1127, 37, 113, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1128, 38, 113, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1129, 39, 113, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1130, 40, 113, 3, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1131, 31, 114, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1132, 32, 114, 1, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1133, 33, 114, 5, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1134, 34, 114, 4, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1135, 35, 114, 5, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1136, 36, 114, 3, 5, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1137, 37, 114, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1138, 38, 114, 5, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1139, 39, 114, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1140, 40, 114, 3, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1141, 31, 115, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1142, 32, 115, 3, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1143, 33, 115, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1144, 34, 115, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1145, 35, 115, 4, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1146, 36, 115, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1147, 37, 115, 2, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1148, 38, 115, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1149, 39, 115, 2, 0, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1150, 40, 115, 2, 5, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1151, 31, 116, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1152, 32, 116, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1153, 33, 116, 3, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1154, 34, 116, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1155, 35, 116, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1156, 36, 116, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1157, 37, 116, 5, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1158, 38, 116, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1159, 39, 116, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1160, 40, 116, 1, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1161, 31, 117, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1162, 32, 117, 4, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1163, 33, 117, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1164, 34, 117, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1165, 35, 117, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1166, 36, 117, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1167, 37, 117, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1168, 38, 117, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1169, 39, 117, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1170, 40, 117, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1171, 31, 118, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1172, 32, 118, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1173, 33, 118, 4, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1174, 34, 118, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1175, 35, 118, 2, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1176, 36, 118, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1177, 37, 118, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1178, 38, 118, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1179, 39, 118, 1, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1180, 40, 118, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1181, 31, 119, 1, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1182, 32, 119, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1183, 33, 119, 2, 5, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1184, 34, 119, 2, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1185, 35, 119, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1186, 36, 119, 2, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1187, 37, 119, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1188, 38, 119, 1, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1189, 39, 119, 2, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1190, 40, 119, 6, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1191, 31, 120, 2, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1192, 32, 120, 1, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1193, 33, 120, 2, 4, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1194, 34, 120, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1195, 35, 120, 4, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1196, 36, 120, 2, 3, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1197, 37, 120, 6, 5, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1198, 38, 120, 3, 2, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1199, 39, 120, 3, 1, '2026-03-28 22:06:21', NULL);
INSERT INTO public.prediction VALUES (1200, 40, 120, 3, 4, '2026-03-28 22:06:21', NULL);


--
-- Data for Name: rule_set; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.rule_set VALUES (5, 5, 1, 0.25, 2, '{"1":300,"2":150,"3":50}');


--
-- Data for Name: special_bet; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.special_bet VALUES (271, 31, 37, 44, NULL, NULL);
INSERT INTO public.special_bet VALUES (272, 31, 38, 40, NULL, NULL);
INSERT INTO public.special_bet VALUES (273, 31, 39, 46, NULL, NULL);
INSERT INTO public.special_bet VALUES (274, 32, 37, 37, NULL, NULL);
INSERT INTO public.special_bet VALUES (275, 32, 38, 44, NULL, NULL);
INSERT INTO public.special_bet VALUES (276, 32, 39, 40, NULL, NULL);
INSERT INTO public.special_bet VALUES (277, 33, 37, 44, NULL, NULL);
INSERT INTO public.special_bet VALUES (278, 33, 38, 40, NULL, NULL);
INSERT INTO public.special_bet VALUES (279, 33, 39, 37, NULL, NULL);
INSERT INTO public.special_bet VALUES (280, 34, 37, 44, NULL, NULL);
INSERT INTO public.special_bet VALUES (281, 34, 38, 37, NULL, NULL);
INSERT INTO public.special_bet VALUES (282, 34, 39, 46, NULL, NULL);
INSERT INTO public.special_bet VALUES (283, 35, 37, 44, NULL, NULL);
INSERT INTO public.special_bet VALUES (284, 35, 38, 40, NULL, NULL);
INSERT INTO public.special_bet VALUES (285, 35, 39, 37, NULL, NULL);
INSERT INTO public.special_bet VALUES (286, 36, 37, 44, NULL, NULL);
INSERT INTO public.special_bet VALUES (287, 36, 38, 46, NULL, NULL);
INSERT INTO public.special_bet VALUES (288, 36, 39, 37, NULL, NULL);
INSERT INTO public.special_bet VALUES (289, 37, 37, 44, NULL, NULL);
INSERT INTO public.special_bet VALUES (290, 37, 38, 40, NULL, NULL);
INSERT INTO public.special_bet VALUES (291, 37, 39, 46, NULL, NULL);
INSERT INTO public.special_bet VALUES (292, 38, 37, 44, NULL, NULL);
INSERT INTO public.special_bet VALUES (293, 38, 38, 46, NULL, NULL);
INSERT INTO public.special_bet VALUES (294, 38, 39, 37, NULL, NULL);
INSERT INTO public.special_bet VALUES (295, 39, 37, 44, NULL, NULL);
INSERT INTO public.special_bet VALUES (296, 39, 38, 46, NULL, NULL);
INSERT INTO public.special_bet VALUES (297, 39, 39, 37, NULL, NULL);
INSERT INTO public.special_bet VALUES (298, 40, 37, 37, NULL, NULL);
INSERT INTO public.special_bet VALUES (299, 40, 38, 44, NULL, NULL);
INSERT INTO public.special_bet VALUES (300, 40, 39, 46, NULL, NULL);
INSERT INTO public.special_bet VALUES (301, 31, 40, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (302, 31, 41, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (303, 31, 42, NULL, 'Hertl', NULL);
INSERT INTO public.special_bet VALUES (304, 32, 40, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (305, 32, 41, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (306, 32, 42, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (307, 33, 40, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (308, 33, 41, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (309, 33, 42, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (310, 34, 40, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (311, 34, 41, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (312, 34, 42, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (313, 35, 40, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (314, 35, 41, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (315, 35, 42, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (316, 36, 40, NULL, 'Sedlák', NULL);
INSERT INTO public.special_bet VALUES (317, 36, 41, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (318, 36, 42, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (319, 37, 40, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (320, 37, 41, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (321, 37, 42, NULL, 'Palát', NULL);
INSERT INTO public.special_bet VALUES (322, 38, 40, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (323, 38, 41, NULL, 'Kubalík', NULL);
INSERT INTO public.special_bet VALUES (324, 38, 42, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (325, 39, 40, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (326, 39, 41, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (327, 39, 42, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (328, 40, 40, NULL, 'Pastrňák', NULL);
INSERT INTO public.special_bet VALUES (329, 40, 41, NULL, 'Nečas', NULL);
INSERT INTO public.special_bet VALUES (330, 40, 42, NULL, 'Červenka', NULL);
INSERT INTO public.special_bet VALUES (331, 31, 43, NULL, NULL, 16);
INSERT INTO public.special_bet VALUES (332, 32, 43, NULL, NULL, 26);
INSERT INTO public.special_bet VALUES (333, 33, 43, NULL, NULL, 15);
INSERT INTO public.special_bet VALUES (334, 34, 43, NULL, NULL, 20);
INSERT INTO public.special_bet VALUES (335, 35, 43, NULL, NULL, 30);
INSERT INTO public.special_bet VALUES (336, 36, 43, NULL, NULL, 15);
INSERT INTO public.special_bet VALUES (337, 37, 43, NULL, NULL, 24);
INSERT INTO public.special_bet VALUES (338, 38, 43, NULL, NULL, 14);
INSERT INTO public.special_bet VALUES (339, 39, 43, NULL, NULL, 28);
INSERT INTO public.special_bet VALUES (340, 40, 43, NULL, NULL, 21);
INSERT INTO public.special_bet VALUES (341, 31, 44, NULL, NULL, 5);
INSERT INTO public.special_bet VALUES (342, 32, 44, NULL, NULL, 11);
INSERT INTO public.special_bet VALUES (343, 33, 44, NULL, NULL, 5);
INSERT INTO public.special_bet VALUES (344, 34, 44, NULL, NULL, 3);
INSERT INTO public.special_bet VALUES (345, 35, 44, NULL, NULL, 6);
INSERT INTO public.special_bet VALUES (346, 36, 44, NULL, NULL, 5);
INSERT INTO public.special_bet VALUES (347, 37, 44, NULL, NULL, 6);
INSERT INTO public.special_bet VALUES (348, 38, 44, NULL, NULL, 5);
INSERT INTO public.special_bet VALUES (349, 39, 44, NULL, NULL, 7);
INSERT INTO public.special_bet VALUES (350, 40, 44, NULL, NULL, 0);
INSERT INTO public.special_bet VALUES (351, 31, 45, NULL, NULL, 8);
INSERT INTO public.special_bet VALUES (352, 32, 45, NULL, NULL, 24);
INSERT INTO public.special_bet VALUES (353, 33, 45, NULL, NULL, 6);
INSERT INTO public.special_bet VALUES (354, 34, 45, NULL, NULL, 12);
INSERT INTO public.special_bet VALUES (355, 35, 45, NULL, NULL, 14);
INSERT INTO public.special_bet VALUES (356, 36, 45, NULL, NULL, 4);
INSERT INTO public.special_bet VALUES (357, 37, 45, NULL, NULL, 16);
INSERT INTO public.special_bet VALUES (358, 38, 45, NULL, NULL, 6);
INSERT INTO public.special_bet VALUES (359, 39, 45, NULL, NULL, 22);
INSERT INTO public.special_bet VALUES (360, 40, 45, NULL, NULL, 6);


--
-- Name: match_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.match_id_seq', 120, true);


--
-- Name: point_entry_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.point_entry_id_seq', 370, true);


--
-- Name: prediction_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.prediction_id_seq', 1200, true);


--
-- Name: rule_set_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.rule_set_id_seq', 5, true);


--
-- Name: special_bet_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.special_bet_id_seq', 360, true);


--
-- Name: special_bet_rule_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.special_bet_rule_id_seq', 45, true);


--
-- Name: team_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.team_id_seq', 48, true);


--
-- Name: tournament_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tournament_id_seq', 5, true);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.user_id_seq', 40, true);


--
-- PostgreSQL database dump complete
--

\unrestrict V7iNbJ1DH0WQ5PtVogSXRQXUW5l5Ev5c49alT0ujdsXGLPYvjiSdmblV4WLWVcG

