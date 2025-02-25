-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2025 at 10:18 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `api`
--
CREATE DATABASE IF NOT EXISTS `api` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `api`;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `answer_text`, `created_at`, `updated_at`) VALUES
(1, 1, 'Aby rozpocząć pracę z systemem, zaloguj się na swoje konto używając otrzymanych danych dostępowych. Po pierwszym logowaniu zalecamy zmianę hasła.', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(2, 2, 'Dokumentację użytkownika znajdziesz w zakładce \"Pomoc\" lub klikając przycisk \"Dokumentacja\" w prawym górnym rogu ekranu.', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(3, 3, 'Aby zmienić hasło, przejdź do ustawień konta (ikona użytkownika -> Ustawienia -> Zmień hasło). Wprowadź aktualne hasło, a następnie dwukrotnie nowe hasło.', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(4, 4, 'Aktualizacja danych profilowych jest dostępna w zakładce \"Mój profil\". Kliknij \"Edytuj\", wprowadź zmiany i zatwierdź przyciskiem \"Zapisz\".', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(5, 5, 'Zalecane praktyki bezpieczeństwa obejmują: regularna zmiana hasła, używanie silnych haseł, włączenie uwierzytelniania dwuskładnikowego, wylogowywanie się po zakończeniu pracy.', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(6, 6, 'Podstawowe zapytanie można wykonać poprzez wpisanie pytania w języku naturalnym w polu wyszukiwania. System automatycznie przekonwertuje je na zapytanie SQL.', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(7, 7, 'Aby wygenerować raport miesięczny, przejdź do zakładki \"Raporty\", wybierz \"Raport miesięczny\", ustaw zakres dat i kliknij \"Generuj\".', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(8, 8, 'API systemu jest dostępne poprzez endpoint /api. Szczegółową dokumentację API znajdziesz w zakładce \"Dokumentacja API\".', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(9, 9, 'Zarządzanie uprawnieniami odbywa się w panelu administratora -> Użytkownicy -> Uprawnienia. Możesz przypisywać role i indywidualne uprawnienia.', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(10, 10, 'Konfiguracja backupu: Panel administratora -> System -> Backup. Ustaw harmonogram, lokalizację i rodzaj kopii zapasowej (pełna/przyrostowa).', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(11, 11, 'Optymalizacja bazy: regularnie aktualizuj statystyki, używaj indeksów, monitoruj zapytania przez EXPLAIN, ustaw odpowiednie parametry cache.', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(12, 12, 'Monitoring aktywności: Panel administratora -> Logi -> Aktywność użytkowników. Możesz filtrować po użytkowniku, dacie, typie operacji.', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(13, 13, 'Tworzenie szablonów raportów: Raporty -> Szablony -> Nowy szablon. Użyj edytora wizualnego lub kodu SQL do definicji zawartości.', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(14, 14, 'Zarządzanie kluczami API: Panel administratora -> API -> Klucze. Możesz generować, dezaktywować i ustawiać limity dla kluczy.', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(15, 15, 'Konfiguracja SMTP: Panel administratora -> System -> Email. Wprowadź dane serwera, port, dane uwierzytelniające i przetestuj połączenie.', '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(16, 16, 'Diagnostyka wydajności: Sprawdź logi serwera, monitoring zasobów, profiler SQL, cache hits/misses, czas odpowiedzi endpoint\'ów.', '2025-02-19 13:34:35', '2025-02-19 13:34:35');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `api_key` varchar(64) NOT NULL,
  `usage_limit` int(11) DEFAULT 1000,
  `used_count` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `user_id`, `api_key`, `usage_limit`, `used_count`, `is_active`, `created_at`) VALUES
(1, 1, 'e183772d0af625eae7322e62858b24ab13fa3c8930c89bca90451d41d54befdf', 1000, 6, 1, '2025-02-19 15:14:37'),
(2, 8, 'e7f9349773fd696d9efe0648c7e68ccb4c04b5ce9cf3d771d9519bbec9ea55cf', 223, 23, 1, '2025-02-19 15:39:07'),
(3, 32, '1a1c822ce6417d2ad813126195dda9220ed96b0d3fbcd7f80c978569f2d60762', 1000, 12, 1, '2025-02-20 09:40:32'),
(5, 40, '26099a0f069c320e72a3fc24530ed09c50d6fb5dd6886650c02b6217075e441d', 1000, 13, 0, '2025-02-20 13:30:37'),
(7, 43, '06afae85c93172ffb819f3e451b45c9adb862a0dcb2106d3896faa56fef5eb40', 12, 10, 0, '2025-02-25 20:28:12');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `api_metrics`
--

CREATE TABLE `api_metrics` (
  `id` int(11) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `endpoint` varchar(255) NOT NULL,
  `response_time` decimal(10,2) NOT NULL,
  `status_code` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `connected_apps`
--

CREATE TABLE `connected_apps` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `provider_id` varchar(255) NOT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `refresh_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `connected_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_used` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `game_scores`
--

CREATE TABLE `game_scores` (
  `id` int(11) NOT NULL,
  `player_name` varchar(50) NOT NULL,
  `score` int(11) NOT NULL,
  `game_name` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `api_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_scores`
--

INSERT INTO `game_scores` (`id`, `player_name`, `score`, `game_name`, `created_at`, `api_key`) VALUES
(44, 'Player', 10, 'snake', '2025-02-25 20:32:09', 'e7f9349773fd696d9efe0648c7e68ccb4c04b5ce9cf3d771d9519bbec9ea55cf'),
(45, 'TestPlayer', 12, 'classic', '2025-02-25 21:00:38', 'e7f9349773fd696d9efe0648c7e68ccb4c04b5ce9cf3d771d9519bbec9ea55cf');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `privacy_settings`
--

CREATE TABLE `privacy_settings` (
  `user_id` int(11) NOT NULL,
  `account_visibility` enum('public','private','friends') DEFAULT 'public',
  `data_sharing` tinyint(1) DEFAULT 0,
  `analytics` tinyint(1) DEFAULT 0,
  `marketing` tinyint(1) DEFAULT 0,
  `third_party` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `privacy_settings`
--

INSERT INTO `privacy_settings` (`user_id`, `account_visibility`, `data_sharing`, `analytics`, `marketing`, `third_party`, `updated_at`) VALUES
(1, 'public', 0, 0, 0, 0, '2025-02-18 08:58:21');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `query_history`
--

CREATE TABLE `query_history` (
  `id` int(11) NOT NULL,
  `user_type` enum('admin','user') NOT NULL,
  `question_text` text NOT NULL,
  `answer_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `query_history`
--

INSERT INTO `query_history` (`id`, `user_type`, `question_text`, `answer_text`, `created_at`) VALUES
(1, 'user', 'Jak zmienić hasło?', 'Aby zmienić hasło, przejdź do ustawień konta...', '2025-02-19 13:34:35'),
(2, 'admin', 'Jak zoptymalizować bazę danych?', 'Optymalizacja bazy wymaga regularnej aktualizacji statystyk...', '2025-02-19 13:34:35'),
(3, 'user', 'Gdzie znajdę raporty?', 'Raporty są dostępne w zakładce \"Raporty\" w głównym menu...', '2025-02-19 13:34:35'),
(4, 'admin', 'Jak utworzyć backup?', 'Backup można utworzyć w panelu administratora...', '2025-02-19 13:34:35'),
(5, 'user', 'co to za aplikacja', 'Jest to aplikacja do zarządzania danymi i generowania raportów. Umożliwia użytkownikom logowanie, zmianę hasła, aktualizację danych profilowych, wykonywanie zapytań do bazy oraz generowanie raportów. Dodatkowo, oferuje API do integracji z innymi systemami.', '2025-02-19 13:43:53');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `question_text` text NOT NULL,
  `is_admin_only` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `category_id`, `question_text`, `is_admin_only`, `created_at`, `updated_at`) VALUES
(1, 1, 'Jak rozpocząć pracę z systemem?', 0, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(2, 1, 'Gdzie znajdę dokumentację użytkownika?', 0, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(3, 2, 'Jak zmienić hasło do konta?', 0, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(4, 2, 'Jak zaktualizować dane profilowe?', 0, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(5, 3, 'Jakie są zalecane praktyki bezpieczeństwa?', 0, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(6, 4, 'Jak wykonać podstawowe zapytanie do bazy?', 0, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(7, 5, 'Jak wygenerować raport miesięczny?', 0, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(8, 6, 'Jak korzystać z API systemu?', 0, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(9, 1, 'Jak zarządzać uprawnieniami użytkowników?', 1, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(10, 3, 'Jak skonfigurować backup systemu?', 1, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(11, 4, 'Jak zoptymalizować wydajność bazy danych?', 1, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(12, 4, 'Jak monitorować aktywność użytkowników w bazie?', 1, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(13, 5, 'Jak tworzyć niestandardowe szablony raportów?', 1, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(14, 6, 'Jak zarządzać kluczami API?', 1, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(15, 7, 'Jak skonfigurować serwer SMTP?', 1, '2025-02-19 13:34:35', '2025-02-19 13:34:35'),
(16, 8, 'Jak diagnozować problemy z wydajnością?', 1, '2025-02-19 13:34:35', '2025-02-19 13:34:35');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `question_categories`
--

CREATE TABLE `question_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_categories`
--

INSERT INTO `question_categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Ogólne', 'Podstawowe informacje o systemie', '2025-02-19 13:34:35'),
(2, 'Konto', 'Zarządzanie kontem użytkownika', '2025-02-19 13:34:35'),
(3, 'Bezpieczeństwo', 'Pytania dotyczące bezpieczeństwa', '2025-02-19 13:34:35'),
(4, 'Baza danych', 'Pytania związane z bazą danych', '2025-02-19 13:34:35'),
(5, 'Raporty', 'Generowanie i analiza raportów', '2025-02-19 13:34:35'),
(6, 'API', 'Pytania dotyczące API', '2025-02-19 13:34:35'),
(7, 'Ustawienia', 'Konfiguracja systemu', '2025-02-19 13:34:35'),
(8, 'Błędy', 'Rozwiązywanie problemów', '2025-02-19 13:34:35');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `otp`, `is_active`) VALUES
(1, 'Kamil', 'chaberkamil5@gmail.com', '12345', '', 1),
(8, 'admin', 'admin@admin.pl', 'admin', '', 1),
(32, 'skszypo', 'skszypoo@gmail.com', '123', '', 1),
(39, 'kamil', 'chaber.kamil@uczen.eduwarszawa.pl', '1234', '', 1),
(40, 'artur', 'ASwedzikowski@eduwarszawa.pl', 'artur123', '', 1),
(42, 'test', 'test@example.com', '', '', 0),
(43, 'Kamil Chaber', 'skszypo@gmail.com', '12345', '', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_preferences`
--

CREATE TABLE `user_preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notifications` tinyint(1) DEFAULT 0,
  `notification_frequency` int(11) DEFAULT 3600000,
  `language` varchar(2) DEFAULT 'pl',
  `theme` varchar(10) DEFAULT 'light',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indeksy dla tabeli `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `api_metrics`
--
ALTER TABLE `api_metrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_api_key` (`api_key`);

--
-- Indeksy dla tabeli `connected_apps`
--
ALTER TABLE `connected_apps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_provider` (`user_id`,`provider`);

--
-- Indeksy dla tabeli `game_scores`
--
ALTER TABLE `game_scores`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `privacy_settings`
--
ALTER TABLE `privacy_settings`
  ADD PRIMARY KEY (`user_id`);

--
-- Indeksy dla tabeli `query_history`
--
ALTER TABLE `query_history`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeksy dla tabeli `question_categories`
--
ALTER TABLE `question_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `api_metrics`
--
ALTER TABLE `api_metrics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `connected_apps`
--
ALTER TABLE `connected_apps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `game_scores`
--
ALTER TABLE `game_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `query_history`
--
ALTER TABLE `query_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `question_categories`
--
ALTER TABLE `question_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Constraints for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD CONSTRAINT `api_keys_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `question_categories` (`id`);

--
-- Constraints for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

--
-- Dumping data for table `pma__export_templates`
--

INSERT INTO `pma__export_templates` (`id`, `username`, `export_type`, `template_name`, `template_data`) VALUES
(1, 'root', 'database', 'api', '{\"quick_or_custom\":\"quick\",\"what\":\"sql\",\"structure_or_data_forced\":\"0\",\"table_select[]\":[\"answers\",\"api_keys\",\"api_metrics\",\"connected_apps\",\"game_scores\",\"privacy_settings\",\"query_history\",\"questions\",\"question_categories\",\"user\",\"user_preferences\"],\"table_structure[]\":[\"answers\",\"api_keys\",\"api_metrics\",\"connected_apps\",\"game_scores\",\"privacy_settings\",\"query_history\",\"questions\",\"question_categories\",\"user\",\"user_preferences\"],\"table_data[]\":[\"answers\",\"api_keys\",\"api_metrics\",\"connected_apps\",\"game_scores\",\"privacy_settings\",\"query_history\",\"questions\",\"question_categories\",\"user\",\"user_preferences\"],\"aliases_new\":\"\",\"output_format\":\"sendit\",\"filename_template\":\"@DATABASE@\",\"remember_template\":\"on\",\"charset\":\"utf-8\",\"compression\":\"none\",\"maxsize\":\"\",\"codegen_structure_or_data\":\"data\",\"codegen_format\":\"0\",\"csv_separator\":\",\",\"csv_enclosed\":\"\\\"\",\"csv_escaped\":\"\\\"\",\"csv_terminated\":\"AUTO\",\"csv_null\":\"NULL\",\"csv_columns\":\"something\",\"csv_structure_or_data\":\"data\",\"excel_null\":\"NULL\",\"excel_columns\":\"something\",\"excel_edition\":\"win\",\"excel_structure_or_data\":\"data\",\"json_structure_or_data\":\"data\",\"json_unicode\":\"something\",\"latex_caption\":\"something\",\"latex_structure_or_data\":\"structure_and_data\",\"latex_structure_caption\":\"Struktura tabeli @TABLE@\",\"latex_structure_continued_caption\":\"Struktura tabeli @TABLE@ (continued)\",\"latex_structure_label\":\"tab:@TABLE@-structure\",\"latex_relation\":\"something\",\"latex_comments\":\"something\",\"latex_mime\":\"something\",\"latex_columns\":\"something\",\"latex_data_caption\":\"Content of table @TABLE@\",\"latex_data_continued_caption\":\"Content of table @TABLE@ (continued)\",\"latex_data_label\":\"tab:@TABLE@-data\",\"latex_null\":\"\\\\textit{NULL}\",\"mediawiki_structure_or_data\":\"structure_and_data\",\"mediawiki_caption\":\"something\",\"mediawiki_headers\":\"something\",\"htmlword_structure_or_data\":\"structure_and_data\",\"htmlword_null\":\"NULL\",\"ods_null\":\"NULL\",\"ods_structure_or_data\":\"data\",\"odt_structure_or_data\":\"structure_and_data\",\"odt_relation\":\"something\",\"odt_comments\":\"something\",\"odt_mime\":\"something\",\"odt_columns\":\"something\",\"odt_null\":\"NULL\",\"pdf_report_title\":\"\",\"pdf_structure_or_data\":\"structure_and_data\",\"phparray_structure_or_data\":\"data\",\"sql_include_comments\":\"something\",\"sql_header_comment\":\"\",\"sql_use_transaction\":\"something\",\"sql_compatibility\":\"NONE\",\"sql_structure_or_data\":\"structure_and_data\",\"sql_create_table\":\"something\",\"sql_auto_increment\":\"something\",\"sql_create_view\":\"something\",\"sql_procedure_function\":\"something\",\"sql_create_trigger\":\"something\",\"sql_backquotes\":\"something\",\"sql_type\":\"INSERT\",\"sql_insert_syntax\":\"both\",\"sql_max_query_size\":\"50000\",\"sql_hex_for_binary\":\"something\",\"sql_utc_time\":\"something\",\"texytext_structure_or_data\":\"structure_and_data\",\"texytext_null\":\"NULL\",\"xml_structure_or_data\":\"data\",\"xml_export_events\":\"something\",\"xml_export_functions\":\"something\",\"xml_export_procedures\":\"something\",\"xml_export_tables\":\"something\",\"xml_export_triggers\":\"something\",\"xml_export_views\":\"something\",\"xml_export_contents\":\"something\",\"yaml_structure_or_data\":\"data\",\"\":null,\"lock_tables\":null,\"as_separate_files\":null,\"csv_removeCRLF\":null,\"excel_removeCRLF\":null,\"json_pretty_print\":null,\"htmlword_columns\":null,\"ods_columns\":null,\"sql_dates\":null,\"sql_relation\":null,\"sql_mime\":null,\"sql_disable_fk\":null,\"sql_views_as_tables\":null,\"sql_metadata\":null,\"sql_create_database\":null,\"sql_drop_table\":null,\"sql_if_not_exists\":null,\"sql_simple_view_export\":null,\"sql_view_current_user\":null,\"sql_or_replace_view\":null,\"sql_truncate\":null,\"sql_delayed\":null,\"sql_ignore\":null,\"texytext_columns\":null}');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"api\",\"table\":\"game_scores\"},{\"db\":\"api\",\"table\":\"user\"},{\"db\":\"api\",\"table\":\"api_keys\"},{\"db\":\"api\",\"table\":\"answers\"},{\"db\":\"api\",\"table\":\"user_preferences\"},{\"db\":\"api\",\"table\":\"remember_tokens\"},{\"db\":\"api\",\"table\":\"question_categories\"},{\"db\":\"api\",\"table\":\"questions\"},{\"db\":\"api\",\"table\":\"query_history\"},{\"db\":\"api\",\"table\":\"privacy_settings\"}]');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2025-02-25 21:18:01', '{\"Console\\/Mode\":\"collapse\",\"lang\":\"pl\",\"NavigationWidth\":0}');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indeksy dla tabeli `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indeksy dla tabeli `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indeksy dla tabeli `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indeksy dla tabeli `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indeksy dla tabeli `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indeksy dla tabeli `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indeksy dla tabeli `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indeksy dla tabeli `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indeksy dla tabeli `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indeksy dla tabeli `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indeksy dla tabeli `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indeksy dla tabeli `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indeksy dla tabeli `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indeksy dla tabeli `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indeksy dla tabeli `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indeksy dla tabeli `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indeksy dla tabeli `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
