-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2025 at 02:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `highbrows_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_faqs`
--

CREATE TABLE `academic_faqs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_faqs`
--

INSERT INTO `academic_faqs` (`id`, `title`, `description`, `created_at`) VALUES
(3, 'What courses does Highbrows Forces Academy offer?', 'We offer preparatory courses for ISSB, PMA Long Course, Air Force, Navy, Cadet Colleges, and written tests. We also provide interview preparation, physical training, and personality grooming.', '2025-06-26 04:51:59'),
(4, 'What courses does Highbrows Forces Academy offer?', 'We offer preparatory courses for ISSB, PMA Long Course, Air Force, Navy, Cadet Colleges, and written tests. We also provide interview preparation, physical training, and personality grooming.', '2025-06-27 07:40:51'),
(7, 'Who teaches at Highbrows Forces Academy?', 'Our faculty includes retired army officers, psychologists, and experienced education professionals who specialize in ISSB', '2025-07-02 08:06:21'),
(8, 'Does the academy provide hostel facilities?', 'Yes, we offer safe and well-maintained hostel facilities with mess, laundry, and basic recreational services for students coming from other cities.', '2025-07-02 08:07:05');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`, `created_at`) VALUES
(10, 'asad', 'asad', '$2y$10$GQ/qhwBw4F0RzBisgOzChOvSO6fYDzo6LtuSvyLb/iJpCUey//ahC', '2025-07-01 05:30:09');

-- --------------------------------------------------------

--
-- Table structure for table `blogs_content`
--

CREATE TABLE `blogs_content` (
  `id` int(11) NOT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `blog_content` longtext DEFAULT NULL,
  `side_image` varchar(255) DEFAULT NULL,
  `side_image2` varchar(255) DEFAULT NULL,
  `category_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs_content`
--

INSERT INTO `blogs_content` (`id`, `main_image`, `title`, `description`, `blog_content`, `side_image`, `side_image2`, `category_name`, `created_at`) VALUES
(15, 'Picture.png', 'Democracy in Pakistan', 'Democracy is the system of government where the power lies in the hands of the people, and in the context of Pakistan', '<p>Democracy is the system of government where the power lies in the hands of the people, and in the context of Pakistan, it has been both a dream and a challenge since the country&rsquo;s creation in 1947. The founders of Pakistan, particularly Quaid-e-Azam Muhammad Ali Jinnah, envisioned a democratic state rooted in justice, equality, and Islamic values. However, the journey of democracy in Pakistan has been rocky, facing constant interruptions from military regimes, political instability, and a lack of strong democratic traditions. After independence, Pakistan adopted a parliamentary system, but soon after, internal conflicts, weak political institutions, and a growing influence of the military began to undermine democratic development. The first constitution came in 1956, yet within two years, it was replaced by martial law. General Ayub Khan&rsquo;s takeover in 1958 marked the beginning of a long cycle of military interventions, with elected governments being repeatedly ousted or sidelined. This interference distorted the democratic process, leading to public mistrust and weakening institutions like the judiciary, legislature, and election commissions.</p>\r\n\r\n<p>Even in periods of civilian rule, democracy in Pakistan has struggled with issues like corruption, dynastic politics, weak governance, and lack of accountability. Political parties often revolve around personalities rather than ideologies, leading to a culture of patronage and favoritism. Elections, though held regularly in recent years, have been marred by allegations of rigging, manipulation, and interference by non-political forces. The role of media, civil society, and judiciary has been both supportive and critical, depending on the context, yet these pillars are also under pressure due to censorship, threats, and politicization. Despite these challenges, the people of Pakistan have shown a strong desire for democratic governance. Voter turnout, political engagement, and public awareness campaigns have increased significantly, especially among youth and urban populations. Pakistan&#39;s democracy is now more participatory than ever before, with social media and technology giving citizens a stronger voice.</p>\r\n\r\n<p>Parliamentary debates, court rulings, and press freedom are examples of democratic elements functioning even amid adversity. The passage of the 18th Amendment in 2010 was a landmark in strengthening parliamentary sovereignty and provincial autonomy. However, democracy cannot be sustained merely by holding elections. True democracy demands rule of law, independent institutions, a free press, respect for human rights, and the inclusion of all voices, especially women, minorities, and marginalized groups. Education and awareness play a crucial role in nurturing democratic values. A literate and informed citizenry is better equipped to question leaders, demand transparency, and make informed electoral choices. Unfortunately, a lack of civic education and widespread poverty often prevents citizens from fully participating in democratic processes.</p>\r\n\r\n<p>To make democracy stronger in Pakistan, reforms are needed in electoral laws, party financing, and judicial independence. Civil-military relations must be redefined, with all institutions working within their constitutional boundaries. A culture of tolerance, dialogue, and mutual respect must replace political polarization and hate speech. It is also vital that democratic norms are taught from school to university level, encouraging future generations to believe in peaceful political engagement. Democracy in Pakistan has come a long way, and while it has many flaws, it remains the most inclusive and just form of governance. The resilience of the people, the vision of our founding fathers, and the commitment of democratic forces offer hope that Pakistan can one day achieve a truly vibrant, representative, and accountable democracy. As citizens, it is our duty to participate actively, hold our leaders accountable, and never give up on the democratic process. A democratic Pakistan is not just a political ideal&mdash;it is a necessity for peace, prosperity, and justice for all.</p>\r\n', '22.jpg', '111.png', 'blogs', '2025-06-26 14:01:07'),
(16, '22.jpg', 'Quaid-e-Azam – The Father of the Nation', 'He was born on December 25, 1876, in Karachi.He came from a merchant family and was raised with strong moral values.\r\nFrom an early age, Jinnah showed signs of brilliance and leadership.', '<p>Quaid-e-Azam Muhammad Ali Jinnah was the founding father of Pakistan.<br />\r\nHe was born on December 25, 1876, in Karachi.He came from a merchant family and was raised with strong moral values.<br />\r\nFrom an early age, Jinnah showed signs of brilliance and leadership.<br />\r\nHe went to England to study law and became a barrister.His education abroad shaped his thinking and broadened his vision.He returned to India to fight for justice and equality through law.Initially, he worked with the Indian National Congress.He believed in Hindu-Muslim unity at the beginning of his career.<br />\r\nBut over time, he realized that Muslims needed their own identity.He joined the All-India Muslim League to safeguard Muslim rights.<br />\r\nHis speeches were powerful and rooted in logic and law.He advocated for constitutional reforms and civil rights.Quaid-e-Azam never wavered in his pursuit of truth and justice.He remained dignified, even when faced with hostility and resistance.<br />\r\nHe believed in peaceful struggle and legal processes.He transformed the Muslim League into a powerful political force.He voiced the concerns of Muslims in every assembly and forum.He taught Muslims to have self-respect and unity.<br />\r\nHis leadership was a beacon of hope in dark times.<br />\r\nThe Lahore Resolution in 1940 was a turning point.It formally demanded a separate homeland for Muslims.This demand was based on the two-nation theory.Jinnah believed that Muslims and Hindus were distinct nations.They had different religions, cultures, and social values.He wanted a land where Muslims could live with dignity.<br />\r\nHe faced immense opposition from Congress and the British.But he remained calm, wise, and firm in his stance.His vision and arguments were respected worldwide.He was known for his honesty and clarity of thought.He gained the title &ldquo;Quaid-e-Azam&rdquo; meaning &ldquo;Great Leader.&rdquo;<br />\r\nPeople followed him with trust and admiration.<br />\r\nHe united Muslims from all regions of India.He worked tirelessly day and night for the cause of Pakistan.He suffered from poor health but never showed weakness.He sacrificed his health for the dream of freedom.He negotiated with the British and Congress leaders.<br />\r\nHe never accepted any compromise against Muslim interests.In 1947, his dream turned into reality with the creation of Pakistan.August 14, 1947, became a day of independence and triumph.<br />\r\nHe became the first Governor-General of Pakistan.He took oath in Karachi and began building the new nation.He laid down principles for governance and nation-building.He emphasized unity, faith, and discipline in his speeches.He believed in equal rights for all citizens.He advocated for religious freedom and justice for minorities.<br />\r\nHe wanted Pakistan to be a progressive, democratic state.He warned against corruption, nepotism, and communal hatred.His visionwasrootedinmodern Islamic principles.He urged youth to educate themselves and serve the nation.He believed in hard work and merit-based progress.His leadership was admired by friend and foe alike.He was a symbol of grace, intellect, and integrity.He dressed sharply and spoke with precision and elegance.<br />\r\nHe was known for his discipline and punctuality.Even his critics acknowledged his commitment to principles.Pakistan faced many challenges after independence.There were refugee crises, financial difficulties, and governance issues.But Quaid-e-Azam remained hopeful and determined.He inspired people to stay strong and united.He visited hospitals, refugee camps, and administrative offices.He worked round the clock despite his fragile health.<br />\r\nHe led by example and asked others to do the sameSadly, he passed away on September 11, 1948.His death was a huge loss forthenation.Thewhole country mourned his passing with deep sorrow.He left behind a legacy of greatness and sacrifice.His mausoleuminKarachi is a symbol of national pride.It is visited by people from all walks of life.<br />\r\nHis life continues to inspire generations of Pakistanis.He taught us that faith and unity can move mountains.<br />\r\nHe proved that one man can change the course of history.He showed that true leadership is about serving others.<br />\r\nToday&rsquo;s Pakistan owes its existence to Quaid-e-Azam&rsquo;s efforts.His vision must be remembered and protected.<br />\r\nWe must uphold the values he fought for.We must fight corruption and promote justice.We must educate our youth and empower our women.Wemust strengthen institutions and reject extremism.We must build a Pakistan based on merit and fairness.We must value diversity and harmony.Quaid-e-Azam&#39;s dream was for a united and peaceful Pakistan.He wanted every citizen to feel safe and proud.<br />\r\nHe wanted the world to see Pakistan as a progressive nation.He believed in democracy and the rule of law.<br />\r\nHe had deep respect for the judiciary and the constitution.He discouraged personality worship and wanted accountability.He always kept the interests of the people first.He did not seek personal fame or wealth.He used his intelligence and influence for collective good.<br />\r\nHe left his comfortable life in Bombay for the freedom struggle.He gave up everything for a cause greater than himself.He was truly selfless and patriotic.e is remembered in textbooks, speeches, and monuments.His quotes are carved on public buildings and hearts.He is the identity of Pakistan and the soul of our freedom.Let us teach our children about his struggles and principles.Let us celebrate his life not just in words, but in actions.Let us turn his dream into a living reality.Let us work hard to build a better Pakistan.Let us stay united and never forget our roots.<br />\r\nLet us be worthy of the freedom he gave us.Let us say proudly, &ldquo;We are the nation of Quaid-e-Azam.&rdquo;</p>\r\n', '33.jpg', '22.jpg', 'blogs', '2025-06-26 14:18:07'),
(17, '1751458420_22.jpg', 'Iqbal – Our Hero', 'Allama Muhammad Iqbal, known as the poet of the East, was a visionary and philosopher.\r\nHe dreamed of a separate homeland for Muslims in the subcontinent.\r\n', '<p>Allama Muhammad Iqbal, known as the poet of the East, was a visionary and philosopher. He dreamed of a separate homeland for Muslims in the subcontinent. His poetry inspired millions to rise against oppression. Iqbal&#39;s words held wisdom, courage, and a call to action.Born on November 9, 1877, in Sialkot, Iqbal grew up in a spiritually enriched environment. He studied in Lahore, Cambridge, and Munich, gaining deep knowledge in philosophy and law.</p>\r\n\r\n<p>Iqbal&rsquo;s thoughts reflected a blend of Eastern spirituality and Western scholarship. This unique combination shaped his identity and influenced his mission.qbal believed in the power of self or &quot;Khudi&quot;, urging people to awaken their inner strength. Through his poetry, he taught Muslims to be fearless and ambitious. He criticized materialism and the decay of spiritual values. He emphasized unity, brotherhood, and divine love.Iqbal&rsquo;s famous poetry book &ldquo;Bang-e-Dra&rdquo; lit a fire in the hearts of the youth. He reminded the Muslim Ummah of their glorious past and potential. His verses were not just poems, but calls to reclaim honor. His writings instilled pride and direction in a scattered nation.&ldquo;Lab Pe Aati Hai Dua Ban Ke Tamanna Meri&rdquo; is a prayer every child recites in school. It reflects his deep concern for future generations. He wanted every</p>\r\n\r\n<p>Muslim to rise like an eagle, brave and visionary. For Iqbal, a child&rsquo;s heart was where the revolution began.qbal is not just a historical figure; he is a timeless teacher. His poetry speaks directly to our soul, especially in times of crisis. He asks us to rebuild our destiny through faith, hard work, and knowledge. He teaches us to remain hopeful even in the darkest times.He admired Islamic history, drawing inspiration from Prophet Muhammad (PBUH) and the Caliphs. He wished to revive Islamic values in the modern age. He condemned slavery of thought and mental colonization. Iqbal was a champion of freedom&mdash;of the body, mind, and spirit.In 1930, Iqbal presented the idea of a separate Muslim state in his Allahabad address. This vision laid the foundation for Pakistan&rsquo;s creation. He believed that Muslims needed a land to practice their faith freely. This speech gave hope to Muslims living under British and Hindu domination.Iqbal passed away on April 21, 1938, before Pakistan became a reality. But his dream lived on in the hearts of millions. His poetry and vision became the guiding light for Quaid-e-Azam Muhammad Ali Jinnah. Iqbal was the spiritual father of Pakistan.Even today, Iqbal&rsquo;s relevance has not faded. In a world filled with confusion, his teachings give clarity.</p>\r\n\r\n<p>He invites us to look within and discover our strength. He reminds us to hold on to our faith and identity.Iqbal believed that the youth are the builders of the nation. He wrote many poems dedicated to awakening their passion. &ldquo;Shaheen&rdquo; or eagle in his poetry symbolizes courage, height, and self-respect. He urged youth to break free from limits and rise above challenges.Khudi ko kar buland itna...&rdquo; is one of his most quoted verses. It tells us to strive so hard that fate itself follows us. This reflects his belief in action and hard work. He saw no success in laziness or dependency.His message was not limited to Muslims of the subcontinent. Iqbal spoke for all humanity. He dreamed of a world where justice, compassion, and truth would prevail. His universal appeal still echoes across borders.The Pakistan we live in today is a result of his dreams. Iqbal&rsquo;s role in shaping national identity cannot be ignored. His poetry is part of school curriculums and public speeches. He remains the heart of Pakistani ideology.Our hero did not carry weapons or lead armies. His words were his weapons, sharper than swords. He changed the destiny of a nation with his pen. Iqbal showed that ideas are mightier than power.In today&#39;s Pakistan, we must revisit Iqbal&#39;s thoughts.</p>\r\n\r\n<p>Are we living up to the values he stood for? Are we promoting education, unity, and character? Iqbal reminds us to reflect and reform.His grave lies near the Badshahi Mosque in Lahore. It is visited by thousands who come to pay tribute. But the best tribute is to implement his message in life. Iqbal&#39;s true resting place is in the hearts of the people.Let us revive Iqbal&rsquo;s message in schools, colleges, and homes. Let his vision guide our policies and principles. Let his poetry be a light in our moments of darkness. Iqbal is not just history&mdash;he is our living inspiration.O youth, listen to Iqbal&rsquo;s call once more. Awaken your soul and take charge of your future. Be fearless, be faithful, be fierce like the eagle. That is how we honor Iqbal&mdash;our true national hero.</p>\r\n', '1751458420_pngwing.com.png', '1751458420_bg.png', 'pakistan', '2025-06-26 14:21:03'),
(19, '1751456554_Picture.png', 'Pakistan-Ek Jameel-o-Khubsurat Mulk', 'Pakistan ki tareekh mein bohat se utar chadhav aaye, lekin har martaba is qom ne azm o himmat ke saath in mushkilaat ka muqabla kiya. Hamara flag sirf ek parcham nahi balkay ek pehchan hai, jis mein sabz rang Islam aur aksariyat ko represent karta hai jabke safed rang minorities ka nummayinda hai. Chaand aur sitara taraqqi aur roshni ki nishani hain.', '<p>Pakistan duniya ke un chand mumalik mein se hai jo apne andar saqafat, tareekh, husn-e-fitrati, aur resources ka bepanah khazana rakhta hai. Yeh mulk 14 August 1947 ko azaad hua, aur is ne apni qom ki mehnat, azm aur qurbaniyon se aaj ek mukammal identity hasil ki hai. Pakistan ka har shehar, har ilaqa aur har rehnay walay mein ek alag jadoo chhupa hua hai. Lahore ki galiyon ka rang, Karachi ki roshaniyan, Islamabad ka sukoon, Peshawar ki saqafat, aur Gilgit-Baltistan ke pahadon ka husn, yeh sab milkar Pakistan ki khoobsurti ko bayan karte hain.Pakistan ke log mehnati, dil se pyar karne walay aur mehmaan-nawaz hain. Yeh mulk tamam mazahib, zabanon aur qoumon ke logon ko ek chhat talay jorne ki salahiyat rakhta hai. Humari jawan nasal mein potential hai duniya ko har field mein lead karne ka, chahe wo technology ho, science ho, sports ho ya culture. Humare students duniya bhar ke universities mein top karte hain, humare engineers aur doctors international standards pe kaam karte hain.</p>\r\n\r\n<p>Pakistan ki tareekh mein bohat se utar chadhav aaye, lekin har martaba is qom ne azm o himmat ke saath in mushkilaat ka muqabla kiya. Hamara flag sirf ek parcham nahi balkay ek pehchan hai, jis mein sabz rang Islam aur aksariyat ko represent karta hai jabke safed rang minorities ka nummayinda hai. Chaand aur sitara taraqqi aur roshni ki nishani hain.Yeh mulk natural beauty se bhara hua hai. North mein snow-capped mountains jaise K2, Nanga Parbat, aur Rakaposhi hain, jabke south mein Arabian Sea ki shoreline. Humare paas lush green fields hain Punjab aur Sindh mein, aur desert regions bhi jese ke Thar. Yeh diversity sirf geographi tak mehdood nahi, balkay saqafat, zaban, aur libaas mein bhi hai. Sindhi topi aur ajrak, Punjabi pagri, Balochi embroidery aur Pashtun chadar &mdash; har aik mein apni kahani hai.Pakistan ki economy bhi progressive direction mein ja rahi hai. Agriculture sector mein wheat, rice, sugarcane aur cotton jaise crops export kiye ja rahe hain. Industry mein textile, leather, aur sports goods prominent hain. Sialkot ka bana hua football FIFA World Cup mein istemal hota hai &mdash; yeh fakhar ki baat hai. Information</p>\r\n\r\n<p>Technology sector mein bhi Pakistan ka naam aaj freelancing aur startups ke zariye duniya bhar mein mashhoor ho raha hai.Pakistan ki siyasi scenario mein kai dafa instability dekhi gayi, lekin har dafa awaam ne apne vote ke zariye behtari ki umeed banayi. Media, judiciary, aur civil society ka role is democracy ko sustain karne mein aham hai.Hamara taleemi nizam behtar ho raha hai. Universities, schools, aur online platforms ke zariye taleem ko har ghar tak pohanchaya ja raha hai. Youth programs, scholarships, aur skill development initiatives ke zariye jawan nasal ko empower kiya ja raha hai.Pakistan ki sports dunia mein cricket sabse zyada popular hai. Humne 1992 ka Cricket World Cup jeeta, aur bohat se match-winning players paida kiye jaise Imran Khan, Wasim Akram, Waqar Younis, Shahid Afridi, aur Babar Azam. Hockey, squash, aur kabaddi mein bhi Pakistan ka naam top pe raha hai. Jahangir Khan aur Jansher Khan squash ke asliy legends hain.Pakistan ki film aur drama industry ne bhi dobara revival dekha hai. Dramas jese &quot;Humsafar&quot;, &quot;Mere Paas Tum Ho&quot;, aur &quot;Suno Chanda&quot; ne sirf mulk mein nahi balkay international audience mein bhi shaandar shohrat hasil ki. Film industry bhi naye ideas, talent aur production quality ke zariye nayi manzilon ki taraf barh rahi hai.Culture ki baat ki jaye to Pakistan mein har festival, har tehwar ek alag rang lata hai. Eid-ul-Fitr, Eid-ul-Adha, Basant, Nowruz, aur Christmas &mdash; sab events celebrate kiye jaate hain. Yeh hamari unity ka saboot hain.Pakistan ke cuisine ki baat karein to yeh duniya bhar mein mashhoor hai. Biryani, Nihari, Haleem, Chapli Kebab, Karahi, aur saag makai ki roti &mdash; har dish mein swad, pyaar aur riwayat basi hui hai. Har shehar ki apni khaas dish hai.Pakistan ke logon ka jazba sab se zyada inspiring hai. Jab bhi koi aafat aye ho &mdash; flood, earthquake, ya terrorism</p>\r\n\r\n<p>&mdash; awam ne mil kar madad ki. Hum ek resilient nation hain.Technology aur innovation ke hawale se bhi Pakistan tarraqqi kar raha hai. Freelancing aur e-commerce mein youth active hai. Startups jaise Bykea, Airlift aur Daraz naye business models introduce kar rahe hain. Digital Pakistan program is youth ko mazid empower kar raha hai.ourism Pakistan mein rapidly grow kar raha hai. Foreign vloggers aur tourists ab Pakistan ko safe aur hospitable mulk keh rahe hain. Hunza Valley, Fairy Meadows, Swat aur Skardu jaise maqamat duniya ke khoobsurat tareen jagahon mein shamil ho gaye hain.Conclusion mein kaha ja sakta hai ke Pakistan sirf ek mulk nahi, ek jazba, ek roohani connection, aur ek pehchan hai. Is mulk mein</p>\r\n\r\n<p>har us shakhs ke liye kuch hai jo aman, mohabbat aur taraqqi mein yaqeen rakhta hai.(P ye paragraph 500 dafa repeat hua hai)Pakistan duniya ke un chand mumalik mein se hai jo apne andar saqafat, tareekh, husn-e-fitrati, aur resources ka bepanah khazana rakhta hai. (x500)</p>\r\n', '1751442200_Picture.png', '1751456487_download (7).jpeg', 'pakistan', '2025-07-01 06:54:46');

-- --------------------------------------------------------

--
-- Table structure for table `cadet_moments`
--

CREATE TABLE `cadet_moments` (
  `id` int(11) NOT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `main_image2` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('full','image_only') NOT NULL DEFAULT 'full'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cadet_moments`
--

INSERT INTO `cadet_moments` (`id`, `main_image`, `main_image2`, `title`, `description`, `type`) VALUES
(21, 'main_image_6864ea95ee9609.28773361.jpeg', 'main_image2_6864ea95eeeb61.85353113.jpeg', 'Leadership in Action', 'Our cadets demonstrate unparalleled leadership skills, working together to conquer challenges. With an emphasis on teamwork and self-discipline, they embody the core values of dedication, respect,and courage.\r\n Every action is a testament to the unwavering commitment of our cadets. From teamwork exercises to advanced combat training, each moment showcases the true essence of leadership\r\n Every action is a testament to the unwavering commitment of our cadets. From teamwork exercises to advanced combat training, each moment showcases the true essence of leadership\r\n Every action is a testament to the unwavering commitment of our cadets. From teamwork exercises to advanced', 'full');

-- --------------------------------------------------------

--
-- Table structure for table `contact_users`
--

CREATE TABLE `contact_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_users`
--

INSERT INTO `contact_users` (`id`, `name`, `email`, `number`, `message`, `submitted_at`) VALUES
(13, 'asad', '11111@gmail.com', 'er3344444444', 'dd', '2025-06-30 08:15:12'),
(14, 'asad', '1111@gmail.com', '03445660474', 'dd', '2025-06-30 08:15:54'),
(15, 'asad', '1111@gmail.com', '03445660474', 'dd', '2025-06-30 08:16:53'),
(16, 'Our store', '123@gmail.com', '03445660474', 'ddd', '2025-07-01 11:12:50');

-- --------------------------------------------------------

--
-- Table structure for table `explore_highbrows`
--

CREATE TABLE `explore_highbrows` (
  `id` int(11) NOT NULL,
  `main_image` varchar(255) NOT NULL,
  `main_image2` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `description2` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `explore_highbrows`
--

INSERT INTO `explore_highbrows` (`id`, `main_image`, `main_image2`, `title`, `description`, `description2`, `created_at`) VALUES
(20, 'img1_68650f79ae7554.70482819.jpeg', 'img2_68650f79ae96c1.22970969.jpeg', 'We are proud that many', 'We are proud that many of our alumni now serve as officers in the Military, Navy, and Air Force. Our structured curriculum ensures readiness through written and interview preparation, command task training, and personality development tailored to military standards.\r\nWe are proud that many of our alumni now serve as officers in the Military, Navy, and Air Force. Our structured curriculum ensures readiness through written and interview preparation, command task training, and personality development tailored to military standards.', 'We are proud that many of our alumni now serve as officers in the Military, Navy, and Air Force. Our structured curriculum ensures readiness through written and interview preparation, command task training, and personality development tailored to military standards.\r\nWe are proud that many of our alumni now serve as officers in the Military, Navy, and Air Force. Our structured curriculum ensures readiness through written and interview preparation, command task training, and personality development tailored to military standards.', '2025-07-01 10:11:04');

-- --------------------------------------------------------

--
-- Table structure for table `explore_highbrows2`
--

CREATE TABLE `explore_highbrows2` (
  `id` int(11) NOT NULL,
  `main_image` varchar(255) NOT NULL,
  `main_image2` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `description2` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `explore_highbrows2`
--

INSERT INTO `explore_highbrows2` (`id`, `main_image`, `main_image2`, `title`, `description`, `description2`, `created_at`) VALUES
(1, 'img1_68650eee7511e8.62585248.jpeg', 'img2_68650eee752cf5.29916997.jpeg', 'Highbrows Forces Academy', 'Highbrows Forces Academy is designed for dedicated candidates aspiring to join the armed forces. With an emphasis on physical fitness, mental sharpness, and leadership grooming, our program provides a complete pre-military experience.\r\nHighbrows Forces Academy is designed for dedicated candidates aspiring to join the armed forces. With an emphasis on physical fitness, mental sharpness, and leadership grooming, our program provides a complete pre-military experience.', 'We are proud that many of our alumni now serve as officers in the Military, Navy, and Air Force. Our structured curriculum ensures readiness through written and interview preparation, command task training, and personality development tailored to military standards.\r\nWe are proud that many of our alumni now serve as officers in the Military, Navy, and Air Force. Our structured curriculum ensures readiness through written and interview preparation, command task training, and personality development tailored to military standards.', '2025-07-02 06:05:30');

-- --------------------------------------------------------

--
-- Table structure for table `heroareas`
--

CREATE TABLE `heroareas` (
  `id` int(11) NOT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `video_content` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `heroareas`
--

INSERT INTO `heroareas` (`id`, `main_image`, `video_content`) VALUES
(63, '/higbrowsnewebsite/backend/uploads/heroareas/main_image_6864ecd39c7db3.18682849.jpeg', ''),
(64, '/higbrowsnewebsite/backend/uploads/heroareas/main_image_6864ecdc5eed49.94895243.jpeg', ''),
(65, '/higbrowsnewebsite/backend/uploads/heroareas/main_image_6864ece5df5561.47071543.jpeg', ''),
(66, '/higbrowsnewebsite/backend/uploads/heroareas/main_image_6864ecef93b8d6.60357755.jpeg', '');

-- --------------------------------------------------------

--
-- Table structure for table `military_moment`
--

CREATE TABLE `military_moment` (
  `id` int(11) NOT NULL,
  `main_image` varchar(255) NOT NULL,
  `main_image2` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `military_moment`
--

INSERT INTO `military_moment` (`id`, `main_image`, `main_image2`, `title`, `description`, `created_at`) VALUES
(9, 'mil_main_6864eb7920c070.54491697.jpeg', 'mil_sec_6864eb792103f2.40318171.jpeg', 'Pak Military Academy (PMA)', 'The Pakistan Military Academy (PMA) is one of the premier training institutions in the country. It has been producing exceptional leaders who go on to serve the nation in various prestigious positions in the Pakistan Army.\r\nLocated in Kakul, it offers rigorous military training combined with academic education. The cadets undergo a challenging journey, preparing them for the duties of leadership and military service.\r\nOver the years, PMA has gained international recognition for its quality of education and training. Many foreign cadets are trained at the academy, strengthening global military ties', '2025-07-02 08:19:05');

-- --------------------------------------------------------

--
-- Table structure for table `our_services`
--

CREATE TABLE `our_services` (
  `id` int(11) NOT NULL,
  `main_image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `our_services`
--

INSERT INTO `our_services` (`id`, `main_image`, `title`, `description`, `created_at`) VALUES
(11, 'service_6864e934d70de6.03787372.png', 'Academic & Excellence<', 'Matric & Intermediate programs with Federal Board syllabus, guided by qualified and experienced faculty.', '2025-07-02 08:09:24'),
(12, 'service_6864e966355df1.88607181.png', 'Military Drills & Physical Training', 'Daily PT, drills, and fitness routines designed to meet the physical standards of armed forces training.', '2025-07-02 08:10:14'),
(13, 'service_6864e99cacd698.06365782.jpeg', 'Leadership Development', 'Roles like cadet captains and platoon leaders promote discipline, confidence, and team-building skills.', '2025-07-02 08:11:08'),
(14, 'service_6864e9c1ec9118.35485254.jpeg', 'Communication & Public Speaking', 'Debates, speeches, and English fluency sessions enhance self-expression and presentation skills.', '2025-07-02 08:11:45'),
(15, 'service_6864e9f10c95e9.99305353.jpeg', 'ISSB & Entry Test Preparation', 'Targeted preparation for ISSB, PMA, Army, Navy, Air Force, and top university admissions tests skills.', '2025-07-02 08:12:33'),
(16, 'service_6864ea1ab1ead6.54226390.jpeg', 'Character Building & Islamic Values', 'Daily Quranic lessons, moral grooming, and disciplined routines to build strong Islamic character.', '2025-07-02 08:13:14');

-- --------------------------------------------------------

--
-- Table structure for table `proud_moment`
--

CREATE TABLE `proud_moment` (
  `id` int(11) NOT NULL,
  `main_image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proud_moment`
--

INSERT INTO `proud_moment` (`id`, `main_image`, `title`, `description`) VALUES
(24, 'proud_685e85a31b02b5.81910004.jpeg', 'Our Proud Moments', 'Every action is a testament to the unwavering commitment of our cadets.'),
(25, 'proud_685e85f13a53b9.63105077.jpeg', 'Leadership in Action', 'Our cadets demonstrate unparalleled leadership skills'),
(26, 'proud_6864ebe781c907.26294028.jpeg', 'Morning Drill Parade', 'Cadets marching in perfect sync.<br>Precision, unity, and discipline.'),
(27, 'proud_6864ec369eefc5.13225909.jpeg', 'Combat & Tactical Training', 'On-ground combat strategy.Courage and tactical awareness.'),
(28, 'proud_6864ec6b9b64a3.42047573.jpeg', 'Passing Out Ceremony', 'Honoring our leaders.Tradition and pride in every salute'),
(29, 'proud_6864ec8c285fc9.77982450.jpeg', 'Weapon Handling Practice', 'Training cadets with skill.Hands-on discipline and safety');

-- --------------------------------------------------------

--
-- Table structure for table `review_content`
--

CREATE TABLE `review_content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `main_video` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `description2` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `video_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review_content`
--

INSERT INTO `review_content` (`id`, `title`, `description`, `main_video`, `thumbnail`, `description2`, `created_at`, `video_url`) VALUES
(91, 'Moniqa Romin', 'Passionate about making Maths and Physics', NULL, 'thumb_68650cefd4f162.13175242.png', 'Dedicated to helping students build a strong foundation.', '2025-07-02 10:41:51', 'https://youtu.be/Qvf5itlVSPs?si=j_qLKx3zzOvsxas8'),
(92, 'Lika Dosti', 'Simplifying Science with engaging visuals', 'review_video_68650d4c23bc74.48163516.mp4', 'thumb_68650d4c242e79.65920247.jpg', 'Dedicated to making Science fun and accessible', '2025-07-02 10:43:24', ''),
(93, 'Rani Kumari', 'Empowering students to become confident', NULL, 'thumb_68650da3a54688.60847145.jpeg', 'Helping students express themselves fluently', '2025-07-02 10:44:51', 'https://youtu.be/Qvf5itlVSPs?si=j_qLKx3zzOvsxas8');

-- --------------------------------------------------------

--
-- Table structure for table `signup_user`
--

CREATE TABLE `signup_user` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_reviews`
--

CREATE TABLE `user_reviews` (
  `id` int(11) NOT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_reviews`
--

INSERT INTO `user_reviews` (`id`, `main_image`, `title`, `description`, `created_at`, `updated_at`) VALUES
(11, 'review_6864e6a1773345.55524585.png', 'Ahsan ali', 'I have got Admission here for cadet college preparation and i had amazing experience with this academy and especially sir imran waheed.', '2025-07-02 07:58:25', '2025-07-02 08:01:43'),
(12, 'review_6864e7c46fdd81.11159798.jpeg', 'Ahmad Mia', 'I had the opportunity to meet with the dynamic & distinguishe faculties, who are highly qualified & friendly patients. With their assist & guidance I… ”', '2025-07-02 08:03:16', '2025-07-02 08:03:16'),
(13, 'review_6864e80ef32d35.98630221.jpg', 'Ali Hamza', 'Amazing Experience with this pre cadet School . Their Cooperation and guidance was remarkable.Very Highly Recommended to everyone.”', '2025-07-02 08:04:30', '2025-07-02 08:04:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_faqs`
--
ALTER TABLE `academic_faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `blogs_content`
--
ALTER TABLE `blogs_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cadet_moments`
--
ALTER TABLE `cadet_moments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_users`
--
ALTER TABLE `contact_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `explore_highbrows`
--
ALTER TABLE `explore_highbrows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `explore_highbrows2`
--
ALTER TABLE `explore_highbrows2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `heroareas`
--
ALTER TABLE `heroareas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `military_moment`
--
ALTER TABLE `military_moment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `our_services`
--
ALTER TABLE `our_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proud_moment`
--
ALTER TABLE `proud_moment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review_content`
--
ALTER TABLE `review_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `signup_user`
--
ALTER TABLE `signup_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_reviews`
--
ALTER TABLE `user_reviews`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_faqs`
--
ALTER TABLE `academic_faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `blogs_content`
--
ALTER TABLE `blogs_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `cadet_moments`
--
ALTER TABLE `cadet_moments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `contact_users`
--
ALTER TABLE `contact_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `explore_highbrows`
--
ALTER TABLE `explore_highbrows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `explore_highbrows2`
--
ALTER TABLE `explore_highbrows2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `heroareas`
--
ALTER TABLE `heroareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `military_moment`
--
ALTER TABLE `military_moment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `our_services`
--
ALTER TABLE `our_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `proud_moment`
--
ALTER TABLE `proud_moment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `review_content`
--
ALTER TABLE `review_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `signup_user`
--
ALTER TABLE `signup_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_reviews`
--
ALTER TABLE `user_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
