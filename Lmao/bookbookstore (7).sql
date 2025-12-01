-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 12:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookbookstore`
--
CREATE DATABASE IF NOT EXISTS `bookbookstore` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bookbookstore`;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `Book_ID` int(11) NOT NULL,
  `Book_Title` varchar(100) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Genre` varchar(50) NOT NULL,
  `Description` varchar(10000) NOT NULL,
  `Photo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`Book_ID`, `Book_Title`, `Price`, `Genre`, `Description`, `Photo`) VALUES
(1001, 'Meow', 20.00, 'HUMOR', 'A novel for your cat, written in its native language. The word \"meow\" repeated over 80,000 times, to rapturous effect. Publisher\'s description: \"Meow meow meow meow meow, meow. Meow meow meow meow. Meow? Meow.\" So begins Sam Austen\'s searing debut novel: an expansive, stream-of-consciousness fusillade decipherable only by cats, which may very well be a raucous, cogent satire laying waste to the literary establishment and the concept of language itself. Or it may not. Here, the experts weigh in: \"Meow meow meow meow meow meow meow, meow meow meow. Meow meow meow meow meow meow, meow meow, meow. Meow meow.\" - Professor Beans, Unaltered Domestic Shorthair \"Meow meow meow, meow meow meow meow! Meow meow meow meow meow meow meow meow. Meow meow meow meow meow meow meow meow meow meow meow meow meow.\" - Constable Stubbs, Breed Indeterminate \"Meow meow, meow meow meow meow meow meow meow meow meow meow. Meow, meow meow meow meow. Meow meow meow meow meow meow meow, meow meow meow meow meow meow meow.\"-Cuddle Princess, Devon Rex (very affectionate!) \"Meow meow meow meow meow. Meow meow. Meow meow meow meow meow meow.\" - Fyodor Dostoevsky ', 'Meow.jpg'),
(1002, 'Woof', 20.00, 'HUMOR', 'Are you a dog lover who understands the unspoken bond between humans and their furry friends? Imagine a book that captures the essence of that connection in the simplest, most delightful way. Introducing \"Woof\" the original novelty book that speaks the language of our beloved canine companions.\r\n\r\n\"Woof\" offers witty insights into a dog’s life. Warning: May cause excessive tail wagging and human giggles! - William Shakespaw\r\n\r\n\"Woof \" is a unique and playful tribute to dogs, filled with page after page of the word \"woof\" and variations of it over 40,000 times! This book isn\'t just about words—it\'s about the joy, love, and humor that dogs bring into our lives.\r\n\r\nFinally, a book your dog won\'t just chew up and forget! - Bark Twain\r\n\r\n\"Woof\" is designed to be a conversation starter, a coffee table piece that brings a smile to every dog lover\'s face. Whether you\'re reading it aloud to your pup or sharing a laugh with fellow dog enthusiasts, \"Woof\" is the perfect way to celebrate the canine connection.\r\n\r\nDon\'t miss out on the chance to own this charming homage to dogs. Order your copy today and experience the joy of a book that truly speaks to dog lovers everywhere.\r\n\r\nYour dog\'s wag of approval is guaranteed!', 'Woof.jpg'),
(1003, 'Peppa\'s Easter Egg Hunt (Peppa Pig)\r\n', 15.00, 'ADVENTURE', 'Peppa and her friends go on an Easter egg hunt adventure! Includes stickers!\r\nIt\'s springtime, and Grandpa Pig has set up an Easter egg hunt for Peppa Pig and her friends! Join Peppa, Rebecca Rabbit, and Freddy Fox as they search for delicious chocolate eggs and even see baby chicks hatching in the yard! Includes stickers!', 'Peppa.jpg'),
(1004, 'Java Cookbook: Problems and Solutions for Java Developers 5th Edition', 25.00, 'EDUCATION', 'As Java continues to evolve, this cookbook continues to grow in tandem with hundreds of hands-on recipes across a broad range of Java topics. Author Ian Darwin gets developers up to speed right away with useful techniques for everything from string handling and functional programming to network communication and AI.\r\n\r\nIf you\'re familiar with any release of Java, this book will bolster your knowledge of the language and its many recent changes, including how to apply them in your day-to-day development. Each recipe includes self-contained code solutions that you can freely use, along with a discussion of how and why they work.\r\n\r\nDownloadable from GitHub, all code examples compile successfully. This updated edition covers changes up to Java 23 and most of Java 24. You will:\r\n\r\nLearn how to apply many new and old Java APIs\r\nUse the new language features in recent Java versions\r\nUnderstand the code you\'re maintaining\r\nDevelop code using standard APIs and good practices\r\nExplore the brave new world of current Java development\r\nIan Darwin has a lifetime of experience in the software industry, having worked with Java across many platforms and types of software, from Java\'s initial pre-release to the present, from desktop to enterprise to mobile.', 'Javacook.jpg'),
(1005, 'JavaScript Cookbook: Programming the Web 3rd Edition', 23.00, 'EDUCATION', 'Why reinvent the wheel every time you run into a problem with JavaScript? This cookbook is chock-full of code recipes for common programming tasks, along with techniques for building apps that work in any browser. You\'ll get adaptable code samples that you can add to almost any project--and you\'ll learn more about JavaScript in the process.\r\n\r\nThe recipes in this book take advantage of the latest features in ECMAScript 2020 and beyond and use modern JavaScript coding standards. You\'ll learn how to:\r\n\r\nSet up a productive development environment with a code editor, linter, and test server\r\nWork with JavaScript data types, such as strings, arrays, and BigInts\r\nImprove your understanding of JavaScript functions, including arrow functions, closures, and generators\r\nApply object-oriented programming concepts like classes and inheritance\r\nWork with rich media in JavaScript, including audio, video, and SVGs\r\nManipulate HTML markup and CSS styles\r\nUse JavaScript anywhere with Node.js\r\nAccess and manipulate remote data with REST, GraphQL, and Fetch\r\nGet started with the popular Express application-building framework\r\nPerform asynchronous operations with Promises, async/await, and web workers', 'Javascriptcook.jpg'),
(1006, 'Harry Potter and the Goblet of Fire (Harry Potter, Book 4) (Interactive Illustrated Edition)', 30.00, 'FANTASY', 'A stunning special edition of the fourth book in the Harry Potter series, illustrated in brilliant full color and featuring eight unique interactive elements, including the Maze at the Triwizard Tournament, the Goblet of Fire itself, and more!\r\nGet ready for adventure in this deluxe special edition of Harry Potter and the Goblet of Fire! J.K. Rowling’s complete and unabridged text is accompanied by full-color illustrations throughout and eight paper-engineered interactive elements: Readers will explore the Weasleys’ tent at the Quidditch World Cup, reveal the Dark Mark in the sky, follow Harry into the Lake at Hogwarts, and more.\r\n\r\nThis keepsake edition is an impressive gift for Harry Potter fans of all ages, a beautiful addition to any collector’s bookshelf, and an enchanting way to share this beloved series with a new generation of readers.', 'Harry.jpg'),
(1007, 'JoJo\'s Bizarre Adventure: Part 7--Steel Ball Run, Vol. 1 (1)', 23.50, 'ADVENTURE', 'A multigenerational tale of the heroic Joestar family and their never-ending battle against evil!\r\nThe legendary Shonen Jump series is now available in deluxe hardcover editions featuring color pages! JoJo’s Bizarre Adventure is a groundbreaking manga famous for its outlandish characters, wild humor, and frenetic battles.\r\n\r\nRiders from around the world gather in the Wild West for the race of the century! Johnny Joestar, a former jockey paralyzed from the waist down, comes to spectate, and momentarily regains the ability to walk while watching a duel fought by Gyro Zeppeli. Desperate to learn more about this power, Johnny joins the race alongside Gyro and embarks on the most epic and bizarre race to ever cross the American frontier!\r\n', 'Jojo.jpg'),
(1008, 'Solo Leveling, Vol. 11 (comic) (Solo Leveling (comic), 11)', 20.50, 'FANTASY', 'After saving Jinho from the vengeful Dongsoo Hwang, Jinwoo makes a public declaration that he will protect his family and friends no matter what. But when a close ally is killed in a sudden attack by the otherworldly Monarchs, Jinwoo is forced to reexamine who it is he wants to protect―and just how far he’ll go to do so.', 'Solo.jpg'),
(1009, 'The Last Viking: The True Story of King Harald Hardrada ', 17.50, 'HISTORY', 'Bloomsbury presents The Last Viking by Don Hollway, read by Mark Meadows.\r\n‘The Last Viking is a masterful and pulse-pounding narrative that transports the reader into the middle of the action.’ Carl Gnam, Military Heritage\r\nHarald Sigurdsson burst into history as a teenaged youth in a Viking battle from which he escaped with little more than his life and a thirst for vengeance. But from these humble origins, he became one of Norway’s most legendary kings. The Last Viking is a fast-moving narrative account of the life of King Harald Hardrada, as he journeyed across the medieval world, from the frozen wastelands of the North to the glittering towers of Byzantium and the passions of the Holy Land, until his warrior death on the battlefield in England.\r\nCombining Norse sagas, Byzantine accounts, Anglo-Saxon chronicles, and even King Harald’s own verse and prose into a single, compelling story, Don Hollway vividly depicts the violence and spectacle of the late Viking era and delves into the dramatic events that brought an end to almost three centuries of Norse conquest and expansion.', 'Viking.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orderlist`
--

CREATE TABLE `orderlist` (
  `Order_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Total` decimal(15,0) NOT NULL,
  `QuantitySum` int(11) NOT NULL,
  `OrderDate` date NOT NULL DEFAULT curdate(),
  `Ways` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderlist`
--

INSERT INTO `orderlist` (`Order_ID`, `User_ID`, `Total`, `QuantitySum`, `OrderDate`, `Ways`) VALUES
(1, 2, 218, 10, '2025-04-28', 'CREDIT'),
(2, 3, 893, 46, '2025-04-28', 'QR'),
(3, 3, 118, 6, '2025-04-28', 'CREDIT');

-- --------------------------------------------------------

--
-- Table structure for table `orderlist_book`
--

CREATE TABLE `orderlist_book` (
  `Order_ID` int(11) NOT NULL,
  `Book_ID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderlist_book`
--

INSERT INTO `orderlist_book` (`Order_ID`, `Book_ID`, `Quantity`, `Subtotal`) VALUES
(1, 1001, 5, 100.00),
(1, 1007, 5, 117.50),
(2, 1001, 2, 40.00),
(2, 1002, 2, 40.00),
(2, 1003, 10, 150.00),
(2, 1004, 1, 25.00),
(2, 1005, 1, 23.00),
(2, 1008, 30, 615.00),
(3, 1006, 1, 30.00),
(3, 1009, 5, 87.50);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `User_ID` int(11) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Photo` varchar(100) NOT NULL,
  `Role` varchar(100) NOT NULL,
  `Address` varchar(1000) NOT NULL,
  `Phone` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`User_ID`, `Email`, `Password`, `Name`, `Photo`, `Role`, `Address`, `Phone`) VALUES
(1, '1@gmail.com', '610a7e75df6fa49e5e30d5a03e10ddb1f0a3e887', 'Coolerjelly', '1.jpg', 'Admin', '131, Jalan Apani 6/1, Taman Apani, 68100 Batu Caves, Selangor.', '60123456789'),
(2, '2@gmail.com', '610a7e75df6fa49e5e30d5a03e10ddb1f0a3e887', 'Yuuuuuan', '680ad8ed29611.jpg', 'Member', '404, Jalan Error 40/4, Taman Error, 50000 Kuala Lumpur, Wilayah Persekutuan, Malaysia.', '601123456789'),
(3, '3@gmail.com', 'd490e46af18126e88889d83d49c23f1b49f118b6', 'Yeeky', '68027a00b4e5c.jpg', 'Member', '51121, Jalan Freakygojo, Taman Freakygojo, 51000 Kuala Lumpur ,Wilayah Persekutuan, Malaysia.', '601134567890'),
(4, '4@gmail.com', '1106baa7d0ed814a80f88a0ddbecf9a32eefefac', 'RickyBoi', '6806ea3f6137d.jpg', 'Member', '911, Jalan Twin, Taman Twin, 50200 Kuala Lumpur, Wilayah Persekutuan, Malaysia.', '60125112151');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`Book_ID`);

--
-- Indexes for table `orderlist`
--
ALTER TABLE `orderlist`
  ADD PRIMARY KEY (`Order_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `orderlist_book`
--
ALTER TABLE `orderlist_book`
  ADD PRIMARY KEY (`Order_ID`,`Book_ID`),
  ADD KEY `Book_ID` (`Book_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `Book_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1010;

--
-- AUTO_INCREMENT for table `orderlist`
--
ALTER TABLE `orderlist`
  MODIFY `Order_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orderlist_book`
--
ALTER TABLE `orderlist_book`
  MODIFY `Order_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderlist`
--
ALTER TABLE `orderlist`
  ADD CONSTRAINT `orderlist_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`);

--
-- Constraints for table `orderlist_book`
--
ALTER TABLE `orderlist_book`
  ADD CONSTRAINT `fk_orderlist_book_order` FOREIGN KEY (`Order_ID`) REFERENCES `orderlist` (`Order_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderlist_book_ibfk_1` FOREIGN KEY (`Book_ID`) REFERENCES `books` (`Book_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
