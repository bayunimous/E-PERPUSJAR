-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 05, 2024 at 10:19 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_skripsi`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(127) NOT NULL,
  `author` varchar(64) NOT NULL,
  `publisher` varchar(64) NOT NULL,
  `isbn` varchar(13) NOT NULL,
  `year` year NOT NULL,
  `rack_id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `book_cover` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `slug`, `title`, `author`, `publisher`, `isbn`, `year`, `rack_id`, `category_id`, `book_cover`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'minus-nihil21580', 'Minus nihil.', 'Chelsea Hana Hastuti', 'Yayasan Sihotang Tbk', '9797796956253', '2021', 5, 1, 'book-3.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(2, 'error-minus99971', 'Error minus.', 'Puspa Astuti S.E.', 'PT Mardhiyah Zulkarnain', '9789001372507', '1975', 8, 5, 'book-2.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(3, 'dolor-sit-necessitatibus-aut-dolores-pariatur43166', 'Dolor sit necessitatibus aut dolores pariatur.', 'Emin Sihotang', 'CV Putra', '9798173519337', '1996', 9, 1, 'book-9.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(4, 'dolor-sit-quia-qui-beatae25568', 'Dolor sit quia qui beatae.', 'Maimunah Padmi Sudiati', 'CV Laksita Haryanti', '9791816300828', '1992', 8, 5, 'book-3.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(5, 'aliquid-earum-quisquam47376', 'Aliquid earum quisquam.', 'Ulya Rahayu S.H.', 'Fa Sinaga Tbk', '9783573808440', '1974', 5, 2, 'book-5.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(6, 'in-commodi-sit77624', 'In commodi sit.', 'Maria Ratna Safitri S.Kom', 'Perum Usada Suwarno (Persero) Tbk', '9787344450364', '2017', 6, 5, 'book-7.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(7, 'ea-est-minima15346', 'Ea est minima.', 'Dipa Limar Budiyanto', 'CV Budiyanto', '9786742693014', '1972', 6, 2, 'book-1.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(8, 'itaque-eos-ullam16475', 'Itaque eos ullam.', 'Diana Lestari', 'CV Sitorus Pangestu', '9795129894111', '2005', 9, 4, 'book-7.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(9, 'expedita-eum-iure14856', 'Expedita eum iure.', 'Parman Widodo', 'Yayasan Maulana Siregar', '9793432228609', '2016', 6, 4, 'book-3.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(10, 'voluptatem-fugit-sed-harum12009', 'Voluptatem fugit sed harum.', 'Tania Ghaliyati Yuniar S.Psi', 'PD Irawan', '9793614759648', '2019', 3, 2, 'book-2.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(11, 'non-quam-molestias-eaque18520', 'Non quam molestias eaque.', 'Karna Pranowo', 'Perum Ramadan (Persero) Tbk', '9788610676396', '1982', 2, 3, 'book-5.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(12, 'aut-est-impedit60684', 'Aut est impedit.', 'Hilda Yolanda', 'PT Gunarto Laksmiwati', '9799397567371', '1990', 6, 4, 'book-7.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(13, 'dolores-totam78036', 'Dolores totam.', 'Nyana Adriansyah', 'Fa Wacana', '9786574375997', '1994', 2, 5, 'book-3.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(14, 'velit-beatae19430', 'Velit beatae.', 'Ganep Tampubolon S.Ked', 'PJ Waluyo', '9797446690803', '1973', 10, 3, 'book-4.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(15, 'aut-totam-ea-vero17792', 'Aut totam ea vero.', 'Cakrawala Hidayanto S.H.', 'UD Aryani', '9798925607107', '1979', 4, 4, 'book-10.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(16, 'qui-et-sunt52012', 'Qui et sunt.', 'Zalindra Kayla Nasyidah M.M.', 'CV Laksmiwati', '9784624485542', '2005', 1, 1, 'book-7.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(17, 'possimus-nostrum-laborum99594', 'Possimus nostrum laborum.', 'Kiandra Restu Yuliarti S.Ked', 'PT Marbun Tbk', '9781662717598', '1996', 8, 1, 'book-9.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(18, 'autem-doloremque-aut89018', 'Autem doloremque aut.', 'Janet Tami Usamah', 'Fa Nugroho (Persero) Tbk', '9789709591729', '1984', 1, 5, 'book-1.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(19, 'sunt-necessitatibus-facilis27495', 'Sunt necessitatibus facilis.', 'Eja Hutasoit', 'PT Lestari Laksita Tbk', '9798319067548', '1991', 2, 4, 'book-9.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(20, 'ipsa-incidunt-molestias21839', 'Ipsa incidunt molestias.', 'Puji Paris Usamah', 'PD Budiyanto', '9794848301450', '1992', 2, 3, 'book-8.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(21, 'ut-quia-voluptatem53862', 'Ut quia voluptatem.', 'Jumari Jailani M.Farm', 'Perum Utama Anggraini', '9782482037767', '2013', 4, 4, 'book-7.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(22, 'iure-cumque-nobis-ratione-possimus57301', 'Iure cumque nobis ratione possimus.', 'Halim Maulana S.Gz', 'UD Prastuti Rahimah', '9790220046940', '1982', 8, 1, 'book-9.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(23, 'sunt-nisi34131', 'Sunt nisi.', 'Baktianto Kardi Mangunsong', 'Fa Hassanah', '9790756448713', '1987', 5, 2, 'book-8.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(24, 'est-maiores30878', 'Est maiores.', 'Uchita Hariyah', 'Yayasan Namaga Kuswoyo Tbk', '9790102196473', '2021', 10, 1, 'book-3.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(25, 'porro-rerum-ab92336', 'Porro rerum ab.', 'Elisa Hafshah Mayasari M.Ak', 'PT Kusmawati Tbk', '9788312733076', '1970', 5, 2, 'book-4.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(26, 'sapiente-doloribus-doloribus33733', 'Sapiente doloribus doloribus.', 'Shania Uyainah S.Ked', 'UD Sitompul', '9781218615651', '1978', 6, 1, 'book-4.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(27, 'voluptate-autem-iste-ea62777', 'Voluptate autem iste ea.', 'Vanya Astuti', 'UD Yulianti', '9780084873998', '2013', 5, 3, 'book-3.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(28, 'soluta-perspiciatis-aut-quae84585', 'Soluta perspiciatis aut quae.', 'Bahuwirya Haryanto', 'CV Nuraini (Persero) Tbk', '9791323261308', '2009', 6, 5, 'book-10.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(29, 'commodi-excepturi-vitae-quo89369', 'Commodi excepturi vitae quo.', 'Julia Jessica Fujiati S.E.I', 'CV Lailasari Yulianti', '9781046194984', '1982', 2, 1, 'book-1.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(30, 'est-nihil-consequatur65716', 'Est nihil consequatur.', 'Gasti Yolanda', 'PT Wijayanti Tbk', '9789773005429', '2017', 6, 3, 'book-4.jpg', '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(31, 'ut-labore-beatae-corrupti-sed-accusamus82003', 'Ut labore beatae corrupti sed accusamus.', 'Cici Hastuti', 'PT Narpati Lailasari (Persero) Tbk', '9785128888839', '1981', 7, 4, 'book-8.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(32, 'architecto-exercitationem-quidem66846', 'Architecto exercitationem quidem.', 'Cager Wibowo', 'UD Setiawan', '9791348344307', '1989', 2, 4, 'book-10.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(33, 'quia-voluptas49501', 'Quia voluptas.', 'Darimin Mahendra', 'PT Manullang (Persero) Tbk', '9796309020559', '1996', 4, 5, 'book-7.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(34, 'sapiente-animi70033', 'Sapiente animi.', 'Mustofa Setiawan', 'PT Hartati Tbk', '9796861144670', '1992', 3, 2, 'book-9.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(35, 'est-dolore-voluptatem-id70683', 'Est dolore voluptatem id.', 'Gantar Ivan Waskita', 'CV Wahyudin', '9794903278246', '2001', 6, 4, 'book-9.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(36, 'aut-eveniet-quidem-expedita33157', 'Aut eveniet quidem expedita.', 'Edward Jailani S.Gz', 'Perum Tampubolon', '9784197963218', '2000', 4, 2, 'book-2.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(37, 'sed-aliquam-quo-eos-placeat20966', 'Sed aliquam quo eos placeat.', 'Makara Hendra Anggriawan', 'Yayasan Uwais Hardiansyah Tbk', '9797385536767', '1981', 9, 2, 'book-4.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(38, 'nemo-architecto-saepe-corporis35695', 'Nemo architecto saepe corporis.', 'Banawi Kusumo', 'Perum Suwarno Tbk', '9783601388845', '2002', 1, 4, 'book-9.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(39, 'dignissimos-illo-animi-et-soluta12964', 'Dignissimos illo animi et soluta.', 'Rini Yuliarti', 'PD Nashiruddin Nasyiah', '9780205603862', '2003', 5, 3, 'book-6.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(40, 'dolor-autem-molestiae26872', 'Dolor autem molestiae.', 'Cakrawala Lazuardi S.Kom', 'Yayasan Rahayu', '9793995522039', '2001', 8, 2, 'book-8.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(41, 'perspiciatis-ratione-maxime-in78156', 'Perspiciatis ratione maxime in.', 'Muni Ajimin Kuswoyo S.H.', 'PJ Prakasa (Persero) Tbk', '9794986182829', '2009', 10, 4, 'book-6.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(42, 'dolorem-tempore-sequi-cumque-molestiae51775', 'Dolorem tempore sequi cumque molestiae.', 'Candrakanta Tomi Pradipta', 'Perum Nugroho', '9784377792102', '1975', 6, 3, 'book-10.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(43, 'ex-vitae10987', 'Ex vitae.', 'Kamaria Talia Nasyidah', 'Fa Farida Budiyanto (Persero) Tbk', '9798808209756', '2018', 2, 1, 'book-3.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(44, 'ut-harum-qui-sequi46778', 'Ut harum qui sequi.', 'Maria Susanti', 'PT Novitasari (Persero) Tbk', '9790934962482', '2008', 6, 4, 'book-4.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(45, 'atque-suscipit90810', 'Atque suscipit.', 'Eman Habibi S.I.Kom', 'PD Rajata Santoso', '9781712042687', '2010', 2, 1, 'book-5.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(46, 'omnis-iusto-quae89142', 'Omnis iusto quae.', 'Elisa Winarsih', 'PD Nashiruddin Tbk', '9780316755740', '2006', 4, 3, 'book-4.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(47, 'voluptatem-nobis-aut-rerum-consequatur-qui90330', 'Voluptatem nobis aut rerum consequatur qui.', 'Makara Saptono', 'PT Winarno', '9791360856178', '1980', 1, 1, 'book-8.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(48, 'aspernatur-sint-aperiam21530', 'Aspernatur sint aperiam.', 'Tantri Anggraini', 'PJ Gunarto (Persero) Tbk', '9795453264833', '1974', 1, 3, 'book-6.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(49, 'quod-mollitia-est-in62011', 'Quod mollitia est in.', 'Panji Rafi Wahyudin S.E.', 'PJ Hakim (Persero) Tbk', '9785615566905', '1977', 3, 4, 'book-8.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(50, 'sunt-dolore-dolor-a25716', 'Sunt dolore dolor a.', 'Michelle Yolanda', 'PJ Hasanah', '9793582442092', '1976', 2, 5, 'book-10.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(51, 'aut-aut83260', 'Aut aut.', 'Jagapati Kuswoyo', 'UD Prabowo Widodo', '9785370002571', '1986', 6, 1, 'book-9.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(52, 'omnis-perferendis-autem-voluptatibus-officiis67827', 'Omnis perferendis autem voluptatibus officiis.', 'Indra Hutagalung', 'Perum Prayoga Susanti', '9798077425642', '1985', 4, 4, 'book-1.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(53, 'ut-sequi-temporibus92594', 'Ut sequi temporibus.', 'Dadap Permadi', 'Perum Maryati Oktaviani', '9783836989428', '1999', 3, 3, 'book-9.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(54, 'rerum-qui-accusantium-aut31034', 'Rerum qui accusantium aut.', 'Gara Nugroho S.I.Kom', 'PJ Laksmiwati Tbk', '9798680108901', '2005', 10, 4, 'book-8.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(55, 'debitis-reprehenderit-dolorem-iusto47501', 'Debitis reprehenderit dolorem iusto.', 'Galur Iswahyudi', 'CV Kusmawati Kusumo (Persero) Tbk', '9797578534099', '1978', 9, 3, 'book-10.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(56, 'ratione-sed-et-qui87149', 'Ratione sed et qui.', 'Jamalia Winarsih', 'Perum Mahendra Kuswandari (Persero) Tbk', '9783920796123', '2011', 2, 4, 'book-5.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(57, 'quos-sunt29853', 'Quos sunt.', 'Widya Kuswandari', 'Perum Susanti Mustofa (Persero) Tbk', '9788651739388', '1997', 4, 1, 'book-6.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(58, 'ipsam-excepturi-qui77390', 'Ipsam excepturi qui.', 'Pangestu Latupono', 'PJ Mayasari Tbk', '9794316057476', '1978', 5, 2, 'book-8.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(59, 'pariatur-a-aut-quasi93725', 'Pariatur a aut quasi.', 'Najwa Elisa Namaga', 'Perum Pudjiastuti', '9797165547662', '1984', 9, 1, 'book-8.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(60, 'ullam-voluptas-rerum-occaecati52051', 'Ullam voluptas rerum occaecati.', 'Melinda Hasanah', 'Perum Wacana Rahmawati', '9798502288286', '2003', 4, 4, 'book-5.jpg', '2024-01-10 17:47:13', '2024-01-10 17:47:13', NULL),
(61, 'sejarah-banjar-623', 'Sejarah Banjar', 'Suriansyah Ideham dkk.', 'Gramedia', '1230401284', '2012', 20, 3, '1706063277_1f3815f5a0afec139e23.jpg', '2024-01-24 01:27:57', '2024-01-24 01:27:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `book_stock`
--

CREATE TABLE `book_stock` (
  `id` bigint UNSIGNED NOT NULL,
  `book_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `book_stock`
--

INSERT INTO `book_stock` (`id`, `book_id`, `quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 33, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(2, 2, 64, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(3, 3, 53, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(4, 4, 59, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(5, 5, 22, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(6, 6, 39, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(7, 7, 9, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(8, 8, 66, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(9, 9, 99, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(10, 10, 38, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(11, 11, 11, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(12, 12, 41, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(13, 13, 70, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(14, 14, 8, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(15, 15, 25, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(16, 16, 91, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(17, 17, 71, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(18, 18, 16, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(19, 19, 18, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(20, 20, 66, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(21, 21, 54, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(22, 22, 78, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(23, 23, 89, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(24, 24, 48, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(25, 25, 33, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(26, 26, 28, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(27, 27, 44, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(28, 28, 17, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(29, 29, 83, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(30, 30, 81, '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(31, 1, 56, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(32, 2, 10, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(33, 3, 79, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(34, 4, 38, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(35, 5, 16, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(36, 6, 76, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(37, 7, 43, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(38, 8, 55, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(39, 9, 20, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(40, 10, 76, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(41, 11, 28, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(42, 12, 23, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(43, 13, 26, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(44, 14, 40, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(45, 15, 22, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(46, 16, 29, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(47, 17, 26, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(48, 18, 7, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(49, 19, 77, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(50, 20, 75, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(51, 21, 88, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(52, 22, 87, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(53, 23, 80, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(54, 24, 33, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(55, 25, 60, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(56, 26, 94, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(57, 27, 27, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(58, 28, 99, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(59, 29, 38, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(60, 30, 93, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(61, 31, 41, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(62, 32, 58, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(63, 33, 24, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(64, 34, 21, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(65, 35, 96, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(66, 36, 32, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(67, 37, 18, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(68, 38, 96, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(69, 39, 92, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(70, 40, 87, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(71, 41, 74, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(72, 42, 12, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(73, 43, 27, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(74, 44, 72, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(75, 45, 37, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(76, 46, 56, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(77, 47, 62, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(78, 48, 33, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(79, 49, 62, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(80, 50, 18, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(81, 51, 24, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(82, 52, 40, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(83, 53, 13, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(84, 54, 56, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(85, 55, 82, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(86, 56, 42, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(87, 57, 94, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(88, 58, 82, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(89, 59, 34, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(90, 60, 46, '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(91, 61, 100, '2024-01-24 01:27:57', '2024-01-24 01:27:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Fiksi', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(2, 'Non-Fiksi', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(3, 'Sejarah', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(4, 'Komik', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(5, 'Teknologi', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(6, 'Fiksi', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(7, 'Non-Fiksi', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(8, 'Sejarah', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(9, 'Komik', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(10, 'Teknologi', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `facilitys`
--

CREATE TABLE `facilitys` (
  `id` int NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `facilitys`
--

INSERT INTO `facilitys` (`id`, `title`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Komputer', 'tester\r\nweg\r\nwe', '2024-07-30 17:56:15', '2024-07-30 18:04:45', NULL),
(2, 'Kipas Angin', 'Kipas Angin Tambahan Diruangan Bermain Anak', '2024-08-04 10:25:33', '2024-08-04 10:25:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `id` int UNSIGNED NOT NULL,
  `loan_id` bigint UNSIGNED DEFAULT NULL,
  `amount_paid` int UNSIGNED DEFAULT NULL,
  `fine_amount` int UNSIGNED NOT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `fines`
--

INSERT INTO `fines` (`id`, `loan_id`, `amount_paid`, `fine_amount`, `paid_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 20000, 20000, '2024-01-27 14:26:00', '2024-01-10 18:47:06', '2024-01-27 06:26:00', NULL),
(2, 4, 15000, 15000, '2023-08-24 09:00:00', '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` varchar(255) NOT NULL,
  `book_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `member_id` int UNSIGNED NOT NULL,
  `loan_date` datetime NOT NULL,
  `due_date` date NOT NULL,
  `return_date` datetime DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `status_loan` varchar(20) NOT NULL,
  `status_return` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id`, `uid`, `book_id`, `quantity`, `member_id`, `loan_date`, `due_date`, `return_date`, `qr_code`, `status_loan`, `status_return`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '356a192b7913b04c54574d18c28d46e6395428ab', 1, 1, 1, '2023-08-21 00:00:00', '2023-08-28', NULL, NULL, 'Setuju', '', '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(2, 'da4b9237bacccdf19c0760cab7aec4a8359010b0', 4, 1, 2, '2023-08-13 00:00:00', '2023-08-20', NULL, NULL, 'Setuju', '', '2024-01-10 18:47:06', '2024-01-10 18:47:06', NULL),
(3, '77de68daecd823babbb58edb1c8e14d7106e83bb', 2, 5, 3, '2023-08-13 00:00:00', '2023-08-20', '2023-08-24 00:00:00', NULL, 'Setuju', 'Setuju', '2024-01-10 18:47:06', '2024-08-01 10:17:43', NULL),
(4, '1b6453892473a467d07372d45eb05abc2031647a', 1, 1, 4, '2023-08-07 00:00:00', '2023-08-21', '2023-08-24 00:00:00', NULL, 'Setuju', 'Setuju', '2024-01-10 18:47:06', '2024-08-01 15:05:18', NULL),
(5, '58eaff6c982a4dc466105a9f0c679f75e28a7f98', 3, 1, 11, '2024-01-22 19:34:38', '2024-01-29', '2024-01-24 09:29:06', NULL, 'Setuju', 'Setuju', '2024-01-22 11:34:38', '2024-08-01 15:07:15', NULL),
(6, '4d7fd653a2bf6e9691ae4bb75f530d6f1f033b9f', 1, 4, 11, '2024-01-22 19:36:52', '2024-02-21', NULL, 'utuh-nanang_minu_efcc7_1705927012.png', 'Setuju', '', '2024-01-22 11:36:52', '2024-01-22 11:36:52', NULL),
(7, 'dd3e007d4681bfd593245b5221829598d7310480', 61, 2, 12, '2024-01-24 09:29:45', '2024-02-23', NULL, 'galuh-banjar_sej_1a547_1706063385.png', 'Setuju', '', '2024-01-24 01:29:45', '2024-01-24 01:29:45', NULL),
(8, '319f9c65f699bfe37b5bb8d9bde0a62993c292c1', 15, 3, 13, '2024-01-27 14:19:52', '2024-02-10', NULL, 'udin-sedunia_aut_53667_1706339992.png', 'Setuju', '', '2024-01-27 06:19:52', '2024-01-27 06:19:52', NULL),
(9, '3f71292fbce2d3a3e4fdee0dedc494158999b3a0', 26, 10, 9, '2024-01-27 14:20:37', '2024-02-26', NULL, 'agnes-hasana_sap_61260_1706340037.png', 'Setuju', '', '2024-01-27 06:20:37', '2024-01-27 06:20:37', NULL),
(10, 'b6320f11621628809d553ce2e9344893c49ccaea', 15, 7, 14, '2024-01-27 14:23:25', '2024-02-10', NULL, 'panglima-tem_aut_5c999_1706340205.png', 'Setuju', '', '2024-01-27 06:23:25', '2024-01-27 06:23:25', NULL),
(11, 'b40249e885e33c141e64467142866bb39b8819c0', 20, 7, 14, '2024-01-27 14:23:25', '2024-02-26', NULL, 'panglima-tem_ips_b6852_1706340205.png', 'Setuju', '', '2024-01-27 06:23:25', '2024-01-27 06:23:25', NULL),
(12, 'e394b15d339426e1657d59d25e5960f946541804', 61, 1, 16, '2024-08-04 10:29:28', '2024-08-11', NULL, 'bayu_sejarah-ban_d9286_1722742168.png', 'Setuju', '', '2024-08-04 02:29:28', '2024-08-04 02:31:24', NULL),
(13, '1f2332409f0db16c2dee55e95d1871df55d86a9d', 61, 2, 17, '2024-08-05 11:21:40', '2024-08-19', NULL, 'fauzi-yusa-r_sej_31a9d_1722831700.png', 'Setuju', '', '2024-08-05 03:21:40', '2024-08-05 03:23:21', NULL),
(14, 'f872454a9c102d35ba66f91eaccabb9e6699d65b', 2, 3, 16, '2024-08-05 17:12:13', '2024-08-19', NULL, 'bayu-nugraha_err_a66ca_1722852733.png', 'Proses', '', '2024-08-05 09:12:13', '2024-08-05 09:12:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int UNSIGNED NOT NULL,
  `uid` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `uid`, `first_name`, `last_name`, `email`, `phone`, `username`, `password`, `address`, `date_of_birth`, `gender`, `qr_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '0c7648cd9e18622f1fedac01415d045e5a808729', 'Jefri', '', 'vanesa.wulandari@mandala.web.id', '0537 7851 989', '', '', 'Jln. Bah Jaya No. 173, Sungai Penuh 30826, DIY', '1999-06-24', 'Male', 'jefri-sitorus_a5d90_1722189250.png', '2024-01-10 17:47:06', '2024-07-30 16:51:34', NULL),
(2, 'e3c153511517c9ab18fd5a19f6619607619de44a', 'Cemplunk', 'Manullang', 'hassanah.gandi@prastuti.go.id', '025 5827 9883', '', '', 'Gg. Basket No. 849, Tual 11984, Kepri', '1998-02-23', 'Male', NULL, '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(3, 'bd6a1af02a23f1d90de32e4a2c39293069c4cb52', 'Nadia', 'Laksita', 'genta87@gmail.co.id', '(+62) 530 3732 599', '', '', 'Ki. Barat No. 464, Bontang 54535, Kaltim', '1998-12-18', 'Female', NULL, '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(4, '651078ae163a3ba3d6d2ed9bca993c9edae03dd3', 'Ida', 'Winarsih', 'andriani.uchita@gmail.com', '0425 2390 3850', '', '', 'Ki. Raya Setiabudhi No. 809, Binjai 11919, Kalbar', '2021-04-09', 'Female', NULL, '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(5, '6efcd58e407b99f5c25501f4f5122bc723b8a5e7', 'Saka', 'Nashiruddin', 'qrajata@yahoo.co.id', '(+62) 495 5778 725', '', '', 'Kpg. Qrisdoren No. 892, Bau-Bau 20616, Kaltara', '1975-10-12', 'Male', NULL, '2024-01-10 17:47:06', '2024-01-10 17:47:06', NULL),
(6, 'b523d0f66d27e7dd0d176f83550eb00184ec87c1', 'Uchita', 'Yolanda', 'najwa.hutasoit@tamba.com', '(+62) 894 4739 189', '', '', 'Dk. Gajah Mada No. 745, Lhokseumawe 90159, Kalbar', '1989-07-07', 'Female', NULL, '2024-01-10 17:47:20', '2024-01-10 17:47:20', NULL),
(7, '4cfe771a0e89d17a9d0becd3831876044e066d05', 'Okto', 'Nugroho', 'cpalastri@yahoo.co.id', '0536 5598 967', '', '', 'Psr. Daan No. 58, Bandar Lampung 78899, Jabar', '1974-09-03', 'Male', NULL, '2024-01-10 17:47:20', '2024-01-10 17:47:20', NULL),
(8, 'fed8fff60e1e5a2508f48b81f52fa15568f40ec3', 'Harjaya', 'Mandala', 'lsudiati@wibisono.go.id', '(+62) 442 3267 1960', '', '', 'Jr. Veteran No. 244, Ambon 98381, NTB', '1982-01-14', 'Male', NULL, '2024-01-10 17:47:20', '2024-01-10 17:47:20', NULL),
(9, '21749e4be2e62bfee2d14f97782bc8748bc84971', 'Agnes', 'Hasanah', 'fitria.nasyiah@yahoo.co.id', '0559 1930 475', '', '', 'Psr. Bakin No. 463, Sorong 13717, Malut', '2014-12-25', 'Female', NULL, '2024-01-10 17:47:20', '2024-01-10 17:47:20', NULL),
(10, 'cb016695fb555da6837a156da22c280dca7e2c69', 'Dimas', 'Sinaga', 'wahyudin.ajimin@agustina.co.id', '(+62) 931 9420 898', '', '', 'Gg. Kartini No. 144, Tangerang Selatan 38743, Jabar', '1979-01-28', 'Male', NULL, '2024-01-10 17:47:20', '2024-01-10 17:47:20', NULL),
(11, '12420cee42a37128d4b859884ecc08b0afe40c9c', 'Utuh', 'Nanang', 'utuhnanang@gmail.com', '+628233939393', '', '', 'Jl. Banjarmasin', '2001-06-26', 'Male', 'utuh-nanang_584c8_1705926601.png', '2024-01-22 11:30:01', '2024-01-22 11:30:01', NULL),
(12, 'ff6179d38456a55d7604f9c60706fb259dd92054', 'Galuh', 'Banjar', 'galuhbanjar@email.com', '+62823812432', '', '', 'Jl. Belitung Darat', '2002-02-06', 'Female', 'galuh-banjar_be925_1706063333.png', '2024-01-24 01:28:54', '2024-01-24 01:28:54', NULL),
(13, '8b1c772b86dbc5ab4ee0d819af070f33bcaaba26', 'Udin', 'Sedunia', 'udinudin@email.com', '+628438284328', '', '', 'Jl. Udin', '2001-12-02', 'Male', 'udin-sedunia_f57a6_1706069292.png', '2024-01-24 03:08:12', '2024-01-24 03:08:12', NULL),
(14, 'bb23abb0b7c655a1dbbe09ebe4f745dab11b6c1a', 'Panglima', 'Tempur', 'panglima@email.com', '+628255555', '', '', 'Jl .Tempur Rakyat', '2001-12-02', 'Male', 'panglima-tempur_3a487_1706340137.png', '2024-01-27 06:22:17', '2024-01-27 06:22:17', NULL),
(15, 'ec8bba1ab8d698cef8da50e41ad004ae74cd5096', 'Muh Syahrul', 'Minanul Aziz', 'msyahrulma@gmail.com', '+6281572323740', 'arul13', '$2y$10$/YKqkI/OAND4Ey0RReBeK.q3rE.vYEoLxJo4BdYqqRDai5zc4ajzm', 'rhtrhr', '2024-07-17', 'Male', 'muh-syahrul-mina_f0179_1722189771.png', '2024-07-28 18:02:51', '2024-07-28 18:02:51', NULL),
(16, '2027863b2d23cd256658b15cc72bc49ad03044eb', 'Bayu', 'Nugraha', 'bayu@email.com', '+6282339449735', 'bayu', '$2y$10$zuUzcQKKl5P8cgLjAOPneeG185uGTbkYHE.1zwHBm6UsIyvA9/v1y', 'Banjarmasin', '2001-06-26', 'Male', 'bayu-nugraha_f57d2_1722814492.png', '2024-07-29 15:45:53', '2024-08-04 22:34:53', NULL),
(17, '8ef6ba4ea0bdb64abbc5e2ea0eb7051b51035747', 'Fauzi', 'Yusa Rahman', 'fauzi@email.com', '6285251117979', 'fauziyr', '$2y$10$DWDmnTzRXPPsEKMJFhhKV.mPMFSvowIlwLdT61PIOEsIGr0KZoaGC', 'Banjarmasin', '1990-08-12', 'Male', 'fauzi-yusa-rahma_73b09_1722831614.png', '2024-08-05 03:20:15', '2024-08-05 03:20:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2020-12-28-223112', 'CodeIgniter\\Shield\\Database\\Migrations\\CreateAuthTables', 'default', 'CodeIgniter\\Shield', 1704912390, 1),
(2, '2021-07-04-041948', 'CodeIgniter\\Settings\\Database\\Migrations\\CreateSettingsTable', 'default', 'CodeIgniter\\Settings', 1704912390, 1),
(3, '2021-11-14-143905', 'CodeIgniter\\Settings\\Database\\Migrations\\AddContextColumn', 'default', 'CodeIgniter\\Settings', 1704912391, 1),
(4, '2023-08-12-000001', 'App\\Database\\Migrations\\CreateRacksTable', 'default', 'App', 1704912391, 1),
(5, '2023-08-12-000002', 'App\\Database\\Migrations\\CreateCategoriesTable', 'default', 'App', 1704912391, 1),
(6, '2023-08-12-000003', 'App\\Database\\Migrations\\CreateBooksTable', 'default', 'App', 1704912391, 1),
(7, '2023-08-12-000004', 'App\\Database\\Migrations\\CreateBookStockTable', 'default', 'App', 1704912391, 1),
(8, '2023-08-12-000005', 'App\\Database\\Migrations\\CreateMembersTable', 'default', 'App', 1704912391, 1),
(9, '2023-08-12-000006', 'App\\Database\\Migrations\\CreateLoansTable', 'default', 'App', 1704912391, 1),
(10, '2023-08-12-000007', 'App\\Database\\Migrations\\CreateFinesTable', 'default', 'App', 1704912391, 1);

-- --------------------------------------------------------

--
-- Table structure for table `performances`
--

CREATE TABLE `performances` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `performances`
--

INSERT INTO `performances` (`id`, `user_id`, `rating`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 4, 'ergerg\r\nreg', '2024-07-30 22:08:41', '2024-07-30 23:37:41', NULL),
(2, 2, 2, 'rgreg\r\nr\r\neg\r\n', '2024-07-30 22:30:15', '2024-07-30 22:37:00', '2024-07-30 15:37:00');

-- --------------------------------------------------------

--
-- Table structure for table `racks`
--

CREATE TABLE `racks` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(8) NOT NULL,
  `floor` varchar(16) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `racks`
--

INSERT INTO `racks` (`id`, `name`, `floor`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '1A', '1', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(2, '1B', '1', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(3, '1C', '1', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(4, '2A', '2', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(5, '2B', '2', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(6, '2C', '2', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(7, '3A', '3', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(8, '3B', '3', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(9, '3C', '3', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(10, '3D', '3', '2024-01-10 18:47:05', '2024-01-10 18:47:05', NULL),
(11, '1A', '1', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(12, '1B', '1', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(13, '1C', '1', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(14, '2A', '2', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(15, '2B', '2', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(16, '2C', '2', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(17, '3A', '3', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(18, '3B', '3', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(19, '3C', '3', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL),
(20, '3D', '3', '2024-01-10 18:47:13', '2024-01-10 18:47:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Cetak Peminjaman', '2024-07-28 18:01:46', '2024-07-28 18:01:46'),
(2, 1, 'Cetak Peminjaman', '2024-07-28 18:01:48', '2024-07-28 18:01:48'),
(3, 2, 'Cetak Denda', '2024-07-28 18:05:57', '2024-07-28 18:05:57'),
(4, 2, 'Cetak Denda', '2024-07-28 18:06:01', '2024-07-28 18:06:01'),
(5, 1, 'Cetak Rak Buku Terlaris', '2024-07-30 16:04:57', '2024-07-30 16:04:57'),
(6, 1, 'Cetak Rak Buku Terlaris', '2024-07-30 16:05:14', '2024-07-30 16:05:14'),
(7, 1, 'Cetak Peminjaman', '2024-07-30 16:24:10', '2024-07-30 16:24:10'),
(8, 1, 'Cetak Peminjaman', '2024-07-30 16:24:47', '2024-07-30 16:24:47'),
(9, 1, 'Cetak Pelayanan Petugas', '2024-07-30 16:36:45', '2024-07-30 16:36:45'),
(10, 1, 'Cetak Pelayanan Petugas', '2024-07-30 16:37:13', '2024-07-30 16:37:13'),
(11, 1, 'Cetak Pelayanan Petugas', '2024-07-30 16:37:33', '2024-07-30 16:37:33'),
(12, 1, 'Cetak Pelayanan Petugas', '2024-07-30 16:37:44', '2024-07-30 16:37:44'),
(13, 1, 'Cetak Rak Buku Terlaris', '2024-07-30 17:40:30', '2024-07-30 17:40:30'),
(14, 2, 'Cetak Penggunaan Fasilitas', '2024-07-30 17:41:20', '2024-07-30 17:41:20'),
(15, 3, 'Cetak Pelayanan Petugas', '2024-07-31 07:51:58', '2024-07-31 07:51:58'),
(16, 3, 'Cetak Pelayanan Petugas', '2024-07-31 07:52:01', '2024-07-31 07:52:01'),
(17, 1, 'Cetak Rak Buku Terlaris', '2024-07-31 07:53:27', '2024-07-31 07:53:27'),
(18, 1, 'Cetak Rak Buku Terlaris', '2024-07-31 07:53:28', '2024-07-31 07:53:28'),
(19, 3, 'Cetak Peminjaman', '2024-07-31 19:02:55', '2024-07-31 19:02:55'),
(20, 3, 'Cetak Peminjaman', '2024-07-31 19:03:29', '2024-07-31 19:03:29'),
(21, 3, 'Cetak Peminjaman', '2024-07-31 19:04:01', '2024-07-31 19:04:01'),
(22, 3, 'Cetak Peminjaman', '2024-07-31 19:04:07', '2024-07-31 19:04:07'),
(23, 3, 'Cetak Peminjaman', '2024-07-31 20:29:02', '2024-07-31 20:29:02'),
(24, 3, 'Cetak Peminjaman', '2024-07-31 20:29:05', '2024-07-31 20:29:05');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `class` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text,
  `type` varchar(31) NOT NULL DEFAULT 'string',
  `context` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `nip` varchar(50) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nip`, `full_name`, `email`, `phone`, `username`, `password`, `role`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '199291291', 'Bayu Nugraha', 'administrator@email.com', '+6282339449736', 'administrator', '$2y$10$VRb8vjS2S4CLtiCMD7oi2e.raNTMCJukqgc5EAE9RD.LgcPdrbfkq', 'Administrator', '2024-01-11 01:46:46', '2024-08-04 10:26:24', NULL),
(2, '102039842', 'Muslimah S.Pd', 'petugas@email.com', '+6282339449735', 'petugas', '$2y$10$Iat8ESA65pM9glFY.pKDse/s2p75AfsD9xChagiCqe04fCVsHmOZu', 'Petugas', '2024-01-24 09:12:09', '2024-08-03 21:21:54', NULL),
(3, '196501201990032006', 'Dra. Hj. Nurliani, M.AP', 'kepdin@email.com', '+628123456789', 'kepdin', '$2y$10$q4.hhkm66IQ6eZ2c4h238.oRlIyqHZ7QscczkFnUMz0BaRo.SLxZq', 'Kepala Dinas', '2024-01-24 09:12:09', '2024-08-03 21:22:19', NULL),
(4, '19738234847', 'Puspita Sari, S. Pd', 'petugas2@email.com', '+682129217575', 'puspitasari', '$2y$10$d/EKpq8TquWBVl7VusrbFehVMWcq1heXZZ/rmosRzS32eJyqVQVHK', 'Petugas', '2024-08-03 20:13:56', '2024-08-04 10:33:13', '2024-08-04 02:33:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `books_rack_id_foreign` (`rack_id`),
  ADD KEY `books_category_id_foreign` (`category_id`);

--
-- Indexes for table `book_stock`
--
ALTER TABLE `book_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_stock_book_id_foreign` (`book_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilitys`
--
ALTER TABLE `facilitys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fines_loan_id_foreign` (`loan_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD KEY `loans_book_id_foreign` (`book_id`),
  ADD KEY `loans_member_id_foreign` (`member_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `performances`
--
ALTER TABLE `performances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `racks`
--
ALTER TABLE `racks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `book_stock`
--
ALTER TABLE `book_stock`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `facilitys`
--
ALTER TABLE `facilitys`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `performances`
--
ALTER TABLE `performances`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `racks`
--
ALTER TABLE `racks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `books_rack_id_foreign` FOREIGN KEY (`rack_id`) REFERENCES `racks` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `book_stock`
--
ALTER TABLE `book_stock`
  ADD CONSTRAINT `book_stock_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fines_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `loans_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
