-- Заполнение справочника отделений
INSERT INTO dict_departments (id, name) VALUES
(1, 'Терапия'),
(2, 'Хирургия'),
(3, 'Неврология'),
(4, 'Офтальмология'),
(5, 'Отоларингология'),
(6, 'Стоматология'),
(7, 'Кардиология'),
(8, 'Гастроэнтерология'),
(9, 'Дерматология'),
(10, 'Урология');

-- Заполнение справочника специализаций
INSERT INTO dict_specializations (id, name) VALUES
(1, 'Терапевт'),
(2, 'Хирург'),
(3, 'Невролог'),
(4, 'Офтальмолог'),
(5, 'Отоларинголог'),
(6, 'Стоматолог'),
(7, 'Кардиолог'),
(8, 'Гастроэнтеролог'),
(9, 'Дерматолог'),
(10, 'Уролог'),
(11, 'Ортопед'),
(12, 'Педиатр'),
(13, 'Гинеколог'),
(14, 'Эндокринолог'),
(15, 'Психиатр');

-- Заполнение справочника целей платежей
INSERT INTO dict_payment_purposes (name) VALUES
('Первичный прием'),
('Повторный прием'),
('Консультация'),
('Диагностика'),
('Лечение'),
('Анализы'),
('Процедуры'),
('Операция'),
('Стоматологические услуги'),
('Ортодонтия'),
('Физиотерапия'),
('Массаж'),
('Косметология'),
('Вакцинация'),
('Скорая помощь');

-- Генерация случайных UUID для повторного использования
DO $$
DECLARE
	uuid1 UUID := gen_random_uuid();
	uuid2 UUID := gen_random_uuid();
	uuid3 UUID := gen_random_uuid();
	uuid4 UUID := gen_random_uuid();
	uuid5 UUID := gen_random_uuid();
	uuid6 UUID := gen_random_uuid();
	uuid7 UUID := gen_random_uuid();
	uuid8 UUID := gen_random_uuid();
	uuid9 UUID := gen_random_uuid();
	uuid10 UUID := gen_random_uuid();
BEGIN
	-- Заполнение прайс-листа
	INSERT INTO pricelist_items (id, nomenklature, category, title, price, "costprice", archived, fixedsalary, fixedagentfee) VALUES
	(uuid1, 'A01.01.001', 1, 'Первичный прием терапевта', 1500, 500, FALSE, 800, 100),
	(uuid2, 'A01.02.001', 2, 'Первичный прием хирурга', 2000, 800, FALSE, 1200, 150),
	(uuid3, 'A01.03.001', 3, 'Первичный прием невролога', 1800, 700, FALSE, 1000, 120),
	(uuid4, 'A01.04.001', 4, 'Первичный прием офтальмолога', 1700, 600, FALSE, 900, 110),
	(uuid5, 'A01.05.001', 5, 'Первичный прием отоларинголога', 1600, 550, FALSE, 850, 105),
	(uuid6, 'A01.06.001', 6, 'Первичный прием стоматолога', 2500, 1000, FALSE, 1500, 200),
	(uuid7, 'A01.07.001', 7, 'Первичный прием кардиолога', 2200, 900, FALSE, 1300, 180),
	(uuid8, 'A01.08.001', 8, 'Первичный прием гастроэнтеролога', 1900, 750, FALSE, 1100, 140),
	(uuid9, 'A01.09.001', 9, 'Первичный прием дерматолога', 1700, 600, FALSE, 900, 110),
	(uuid10, 'A01.10.001', 10, 'Первичный прием уролога', 2000, 800, FALSE, 1200, 150);

	-- Заполнение истории стоимости услуг
	INSERT INTO pricelist_items_snapshot (id, nomenklature, id_pricelist_item, price, costPrice, fixedSalary, fixedAgentFee, date_start, date_finish) VALUES
	(gen_random_uuid(), 'A01.01.001', uuid1, 1400, 450, 750, 90, '2022-01-01', '2022-12-31'),
	(gen_random_uuid(), 'A01.01.001', uuid1, 1500, 500, 800, 100, '2023-01-01', NULL),
	(gen_random_uuid(), 'A01.02.001', uuid2, 1900, 750, 1100, 140, '2022-01-01', '2022-12-31'),
	(gen_random_uuid(), 'A01.02.001', uuid2, 2000, 800, 1200, 150, '2023-01-01', NULL),
	(gen_random_uuid(), 'A01.06.001', uuid6, 2400, 950, 1400, 190, '2022-01-01', '2022-12-31'),
	(gen_random_uuid(), 'A01.06.001', uuid6, 2500, 1000, 1500, 200, '2023-01-01', NULL);
END $$;

-- Заполнение паспортных данных
INSERT INTO passports (id, gender, series_number, birthday, issue_date, authority) VALUES
(gen_random_uuid(), 'M', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', '1980-05-15', '2010-06-20', 'eyJpdiI6IjhLSUlOTXYyN0ErWTMyNEVjQjF1SFE9PSIsInZhbHVlIjoiK1BvTnNVU05XVlBEYzk4aFZxUWdGSWVwT2F1MENKVXAzVUFiM0FwVkUvZz0iLCJtYWMiOiIyOWU1ZWEzOTA5ZGI4NjVkMzE4OGM3NzYwYjBmODliNTNlMzBjY2I1ODljMWZmNTE3ZWE0OGNhMjJmNmI0Y2ZlIiwidGFnIjoiIn0='),
(gen_random_uuid(), 'F', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', '1985-07-22', '2015-08-12', 'eyJpdiI6IjhLSUlOTXYyN0ErWTMyNEVjQjF1SFE9PSIsInZhbHVlIjoiK1BvTnNVU05XVlBEYzk4aFZxUWdGSWVwT2F1MENKVXAzVUFiM0FwVkUvZz0iLCJtYWMiOiIyOWU1ZWEzOTA5ZGI4NjVkMzE4OGM3NzYwYjBmODliNTNlMzBjY2I1ODljMWZmNTE3ZWE0OGNhMjJmNmI0Y2ZlIiwidGFnIjoiIn0='),
(gen_random_uuid(), 'M', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', '1975-09-30', '2018-03-15', 'eyJpdiI6IjhLSUlOTXYyN0ErWTMyNEVjQjF1SFE9PSIsInZhbHVlIjoiK1BvTnNVU05XVlBEYzk4aFZxUWdGSWVwT2F1MENKVXAzVUFiM0FwVkUvZz0iLCJtYWMiOiIyOWU1ZWEzOTA5ZGI4NjVkMzE4OGM3NzYwYjBmODliNTNlMzBjY2I1ODljMWZmNTE3ZWE0OGNhMjJmNmI0Y2ZlIiwidGFnIjoiIn0='),
(gen_random_uuid(), 'F', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', '1990-11-10', '2020-04-25', 'eyJpdiI6IjhLSUlOTXYyN0ErWTMyNEVjQjF1SFE9PSIsInZhbHVlIjoiK1BvTnNVU05XVlBEYzk4aFZxUWdGSWVwT2F1MENKVXAzVUFiM0FwVkUvZz0iLCJtYWMiOiIyOWU1ZWEzOTA5ZGI4NjVkMzE4OGM3NzYwYjBmODliNTNlMzBjY2I1ODljMWZmNTE3ZWE0OGNhMjJmNmI0Y2ZlIiwidGFnIjoiIn0='),
(gen_random_uuid(), 'M', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', '1988-03-18', '2019-07-30', 'eyJpdiI6IjhLSUlOTXYyN0ErWTMyNEVjQjF1SFE9PSIsInZhbHVlIjoiK1BvTnNVU05XVlBEYzk4aFZxUWdGSWVwT2F1MENKVXAzVUFiM0FwVkUvZz0iLCJtYWMiOiIyOWU1ZWEzOTA5ZGI4NjVkMzE4OGM3NzYwYjBmODliNTNlMzBjY2I1ODljMWZmNTE3ZWE0OGNhMjJmNmI0Y2ZlIiwidGFnIjoiIn0=');

-- Заполнение пользователей
INSERT INTO users (id, login, password, second_name, first_name, patronymic_name, post) VALUES
(gen_random_uuid(), 'admin', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'Администратор'),
(gen_random_uuid(), 'doctor1', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'Главный врач'),
(gen_random_uuid(), 'doctor2', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'Врач-терапевт'),
(gen_random_uuid(), 'reception', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'Регистратор'),
(gen_random_uuid(), 'accountant', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'Бухгалтер');

-- Заполнение уровней доступа
INSERT INTO access_level (name) VALUES
('Администратор'),
('Врач'),
('Регистратор'),
('Бухгалтер'),
('Медсестра');

-- Связь пользователей с уровнями доступа
INSERT INTO users_access_level (id_user, id_access_level)
SELECT u.id, a.id
FROM users u, access_level a
WHERE u.login = 'admin' AND a.id = 1;

INSERT INTO users_access_level (id_user, id_access_level)
SELECT u.id, a.id
FROM users u, access_level a
WHERE u.login = 'doctor1' AND a.id = 2;

INSERT INTO users_access_level (id_user, id_access_level)
SELECT u.id, a.id
FROM users u, access_level a
WHERE u.login = 'doctor2' AND a.id = 2;

INSERT INTO users_access_level (id_user, id_access_level)
SELECT u.id, a.id
FROM users u, access_level a
WHERE u.login = 'reception' AND a.id = 3;

INSERT INTO users_access_level (id_user, id_access_level)
SELECT u.id, a.id
FROM users u, access_level a
WHERE u.login = 'accountant' AND a.id = 4;

-- Заполнение данных о пациентах
DO $$
DECLARE
	passport1 UUID := (SELECT id FROM passports LIMIT 1 OFFSET 0);
	passport2 UUID := (SELECT id FROM passports LIMIT 1 OFFSET 1);
	passport3 UUID := (SELECT id FROM passports LIMIT 1 OFFSET 2);
	passport4 UUID := (SELECT id FROM passports LIMIT 1 OFFSET 3);
	passport5 UUID := (SELECT id FROM passports LIMIT 1 OFFSET 4);
BEGIN
	INSERT INTO patients (id, second_name, first_name, patronymic_name, phone_number, balance, passport, info) VALUES
	(gen_random_uuid(), 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 5000, passport1, 'Аллергия на пенициллин'),
	(gen_random_uuid(), 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', p'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 3000, passport2, 'Хронический гастрит'),
	(gen_random_uuid(), 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 7000, passport3, 'Гипертония'),
	(gen_random_uuid(), 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 2000, passport4, 'Беременность 20 недель'),
	(gen_random_uuid(), 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', pgp_sym_encrypt('+79165234567', 'secret_key'), 10000, passport5, 'Спортсмен, травмы колена');
END $$;

-- Заполнение чеков
INSERT INTO receipts (id, total_amount, discount, created_at) VALUES
(gen_random_uuid(), 1500, 0, '2023-01-10 09:15:00'),
(gen_random_uuid(), 2000, 100, '2023-01-11 10:30:00'),
(gen_random_uuid(), 1800, 0, '2023-01-12 11:45:00'),
(gen_random_uuid(), 2500, 200, '2023-01-13 14:20:00'),
(gen_random_uuid(), 1700, 0, '2023-01-14 16:00:00');

-- Заполнение планов лечения
INSERT INTO treatment_plans (id, patient, kind, starting_date, expiration_date)
SELECT
	gen_random_uuid(),
	p.id,
	CASE
		WHEN random() < 0.3 THEN 'Стандартный'
		WHEN random() < 0.6 THEN 'Индивидуальный'
		ELSE 'Комплексный'
	END,
	CURRENT_DATE - (random() * 30)::integer,
	CURRENT_DATE + (30 + (random() * 60)::integer)
FROM patients p
LIMIT 5;

-- Заполнение данных о врачах
DO $$
DECLARE
	user1 UUID := (SELECT id FROM users WHERE login = 'doctor1');
	user2 UUID := (SELECT id FROM users WHERE login = 'doctor2');
	patient1 UUID := (SELECT id FROM patients LIMIT 1 OFFSET 0);
	patient2 UUID := (SELECT id FROM patients LIMIT 1 OFFSET 1);
BEGIN
	INSERT INTO doctors (id, second_name, first_name, patronymic_name, phone_number, birth_date, department, service_duration, default_cabinet, balance, info, id_user, as_patient, rating) VALUES
	(gen_random_uuid(), 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', p'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', '1975-04-12', 1, 600, 101, 15000, 'Опыт работы 20 лет', user1, patient1, 4.8),
	(gen_random_uuid(), 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', '1985-08-25', 1, 600, 102, 12000, 'Кандидат медицинских наук', user2, patient2, 4.9),
	(gen_random_uuid(), 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9','eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', '1980-11-30', 2, 600, 201, 18000, 'Хирург высшей категории', NULL, NULL, 4.7),
	(gen_random_uuid(), 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', '1978-05-15', 6, 600, 301, 25000, 'Стоматолог-ортопед', NULL, NULL, 4.9),
	(gen_random_uuid(), 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', 'eyJpdiI6IlBMM3NUZWl0TnFnRmFWSk00NVBPUmc9PSIsInZhbHVlIjoiSGJobFhybnNFZGxXc1R1QnBkTlgrQT09IiwibWFjIjoiZjhmZjQ0ODJlOWY1YTU3MmUxNDliYWFhZjQ2OGM0OWQzZDRiMDE4ZGVmOGE1YmEwN2U0N2FkMDc1MzQyYjg0ZCIsInRhZyI6IiJ9', '1990-02-28', 7, 600, 202, 10000, 'Молодой специалист', NULL, NULL, 4.5);
END $$;

-- Заполнение данных о зарплатах врачей
INSERT INTO salaries (id, type, rate, monthly_amount, hourly_amount, doctor)
SELECT
	gen_random_uuid(),
	CASE
		WHEN random() < 0.3 THEN 'Сдельная'
		WHEN random() < 0.6 THEN 'Ежемесячная'
		ELSE 'Почасовая'
	END,
	CASE WHEN random() < 0.3 THEN 20 + (random() * 30)::integer ELSE NULL END,
	CASE WHEN random() < 0.3 THEN 50000 + (random() * 100000)::integer ELSE NULL END,
	CASE WHEN random() < 0.3 THEN 500 + (random() * 1000)::integer ELSE NULL END,
	d.id
FROM doctors d;

-- Заполнение истории зарплат
INSERT INTO salaries_snapshot (id, type, rate, monthly_amount, hourly_amount, doctor, date_start, date_finish)
SELECT
	gen_random_uuid(),
	s.type,
	s.rate,
	s.monthly_amount,
	s.hourly_amount,
	s.doctor,
	CURRENT_DATE - (365 + (random() * 365)::integer),
	CURRENT_DATE - (random() * 365)::integer
FROM salaries s
ORDER BY random()
LIMIT 3;

-- Заполнение расписания врачей
INSERT INTO doctor_schedules (id, doctor_id, cabinet, starting, ending)
SELECT
	gen_random_uuid(),
	d.id,
	d.default_cabinet,
	CURRENT_DATE + 1 + (n * interval '1 day') + interval '9 hours',
	CURRENT_DATE + 1 + (n * interval '1 day') + interval '13 hours'
FROM doctors d
CROSS JOIN generate_series(0, 4) AS n
WHERE n < 5;

-- Добавление вечерних смен для некоторых врачей
INSERT INTO doctor_schedules (id, doctor_id, cabinet, starting, ending)
SELECT
	gen_random_uuid(),
	d.id,
	d.default_cabinet,
	CURRENT_DATE + 1 + (n * interval '1 day') + interval '14 hours',
	CURRENT_DATE + 1 + (n * interval '1 day') + interval '18 hours'
FROM doctors d
CROSS JOIN generate_series(0, 2) AS n
WHERE n < 3 AND d.id IN (SELECT id FROM doctors LIMIT 2);

-- Заполнение записей на прием
INSERT INTO patient_appointments (id, scheduled_time, duration, patient_id, status, registration_date, registrar, receipt_id, schedule_id, patient_comment, sms_notification_sent)
SELECT
	gen_random_uuid(),
	ds.starting + (random() * (ds.ending - ds.starting - interval '30 minutes')),
	600,
	p.id,
	CASE
		WHEN random() < 0.2 THEN 'Зарегистрирован'
		WHEN random() < 0.4 THEN 'Пришел'
		WHEN random() < 0.6 THEN 'На приеме'
		ELSE 'Завершен'
	END,
	CURRENT_TIMESTAMP - (random() * interval '7 days'),
	(SELECT id FROM users WHERE login = 'reception'),
	(SELECT id FROM receipts ORDER BY random() LIMIT 1),
	ds.id,
	CASE WHEN random() < 0.3 THEN 'Болит горло' WHEN random() < 0.6 THEN 'Консультация' ELSE NULL END,
	random() < 0.7
FROM doctor_schedules ds
CROSS JOIN patients p
ORDER BY random()
LIMIT 10;

-- Заполнение платежей
INSERT INTO payments (id, date, purpose, details, methods, receipt_id, created_by, doctor_id, patient_id)
SELECT
	gen_random_uuid(),
	r.created_at,
	(SELECT id FROM dict_payment_purposes ORDER BY random() LIMIT 1),
	'Оплата медицинских услуг',
	jsonb_build_object('cash', r.total_amount * 0.5, 'card', r.total_amount * 0.5),
	r.id,
	(SELECT id FROM users WHERE login = 'reception'),
	(SELECT id FROM doctors ORDER BY random() LIMIT 1),
	(SELECT id FROM patients ORDER BY random() LIMIT 1)
FROM receipts r;

-- Связь врачей с услугами
INSERT INTO doctors_pricelist_items (id_doctor, id_pricelist_item, is_basic)
SELECT
	d.id,
	pi.id,
	CASE WHEN random() < 0.5 THEN TRUE ELSE FALSE END
FROM doctors d
CROSS JOIN pricelist_items pi
WHERE (d.department = pi.category OR random() < 0.3)
ORDER BY random()
LIMIT 15;

-- Связь врачей со специализациями
INSERT INTO doctors_specializations (id_doctor, id_specialization, is_basic)
SELECT
	d.id,
	s.id,
	CASE WHEN random() < 0.5 THEN TRUE ELSE FALSE END
FROM doctors d
CROSS JOIN dict_specializations s
WHERE (d.department = s.id OR random() < 0.3)
ORDER BY random()
LIMIT 15;

-- Связь прайс-листа с планами лечения
INSERT INTO pricelist_items_treatment_plans (id_pricelist_item, id_treatment_plan)
SELECT
	pi.id,
	tp.id
FROM pricelist_items pi
CROSS JOIN treatment_plans tp
ORDER BY random()
LIMIT 10;

-- Заполнение медицинских услуг
INSERT INTO medical_services (id, pricelist_item_id, treatment_plan_price, quantity, performer_id, agent_id, receipt_id, date, created_at)
SELECT
	gen_random_uuid(),
	pi.id,
	CASE WHEN random() < 0.5 THEN pi.price * 0.9 ELSE NULL END,
	1 + (random() * 3)::integer,
	(SELECT id FROM doctors ORDER BY random() LIMIT 1),
	(SELECT id FROM doctors ORDER BY random() LIMIT 1),
	r.id,
	r.created_at - (random() * interval '2 hours'),
	r.created_at - (random() * interval '3 hours')
FROM pricelist_items pi
CROSS JOIN receipts r
ORDER BY random()
LIMIT 10;

-- Заполнение шаблонов чеков
INSERT INTO check_templates (id, title, discount, created_at, medical_service)
SELECT
	gen_random_uuid(),
	'Шаблон ' || (row_number() OVER ()),
	5 + (random() * 15)::integer,
	CURRENT_TIMESTAMP - (random() * interval '30 days'),
	ms.id
FROM medical_services ms
ORDER BY random()
LIMIT 3;

-- Заполнение записей о приемах
INSERT INTO notes (id, text, created_at, created_by, updated_at, updated_by, doctor_schedule, patient_appointment)
SELECT
	gen_random_uuid(),
	CASE
		WHEN random() < 0.3 THEN 'Пациент жалуется на головную боль'
		WHEN random() < 0.6 THEN 'Рекомендовано пройти обследование'
		ELSE 'Назначены лекарства'
	END,
	pa.scheduled_time + interval '10 minutes',
	(SELECT id FROM users WHERE login = 'doctor1'),
	pa.scheduled_time + interval '20 minutes',
	(SELECT id FROM users WHERE login = 'doctor1'),
	pa.schedule_id,
	pa.id
FROM patient_appointments pa
WHERE pa.status = 'Завершен'
ORDER BY random()
LIMIT 5;

-- Заполнение отчетов о кассе
INSERT INTO report (startingCash, Date_Cash, created_at, created_by) VALUES
(5000, CURRENT_DATE - 2, CURRENT_DATE - 2 + interval '8 hours', (SELECT id FROM users WHERE login = 'accountant')),
(5500, CURRENT_DATE - 1, CURRENT_DATE - 1 + interval '8 hours', (SELECT id FROM users WHERE login = 'accountant')),
(6000, CURRENT_DATE, CURRENT_DATE + interval '8 hours', (SELECT id FROM users WHERE login = 'accountant'));
