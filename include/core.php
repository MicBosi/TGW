<?php
function transform_str_in_filename($string)
{
    // Trim
    $string = trim($string);
    
    // Transform unallowed /\:?*"<>| and spaces chars in -
    for ($i = 0; $i < strlen($string); $i++) {
        switch ($string[$i]) {
            case "/":
            case "\\":
            case ":":
            case "?":
            case "*":
            case "<":
            case ">":
            case " ":
            case "|":
            case "'":
            case "\"":
                $string[$i] = "-";
                break;
            default:;
        }
    }
    
    // Trim -
    $string = trim($string, "-");
    
    // Remove double inners -
    $newstring = "";
    $lastchar  = "";
    for ($i = 0; $i < strlen($string); $i++) {
        if ($lastchar == "-" && $string[$i] == "-")
            continue;
        $lastchar  = $string[$i];
        $newstring = $newstring . $lastchar;
    }
    
    return $newstring;
}

function write_prev_next()
{
    global $content_file;
    global $contents_dir;
    $siblings = prev_next_map($content_file . ".html");
    
    if ($siblings == FALSE) {
        return;
    }
    
    $prev_info = isset($siblings['prev']) ? extract_header_info("$contents_dir/" . $siblings['prev']) : FALSE;
    $next_info = isset($siblings['next']) ? extract_header_info("$contents_dir/" . $siblings['next']) : FALSE;
    
    // $output = "<center>";
    $output = "<br /><hr noshade size=1 color=black width=85% align=center />\n";
    $output .= "<table class=\"nextprev\" border=0 cellpadding=0 cellspacing=0 width=100%><tr><td align=left valign=middle>\n";
    
    if (isset($siblings["prev"])) {
        $prev = $siblings["prev"];
        $output .= " < <a href=\"" . encode_href_doc($prev) . "\" title=\"" . $prev_info["description"] . "\">" . $prev_info["title"] . " </a>\n";
    } else
        $output .= "&nbsp;";
    
    $output .= "</td><td align=right valign=middle>\n";
    // $output .= " < prev | next > ";
    
    if (isset($siblings["next"])) {
        $next = $siblings["next"];
        $output .= "<a href=\"" . encode_href_doc($next) . "\" title=\"" . $next_info["description"] . "\">" . $next_info["title"] . " </a> > \n";
    } else
        $output .= "&nbsp;";
    $output .= "</td></tr></table>\n";
    // $output .= "</center>";
    
    echo $output;
}

function encode_href_doc($document)
{
    global $base_website_address;
    
    return "$base_website_address/$document";
}

function print_msg($msg)
{
    echo "$msg<br>\n";
}

function parse_menu($file_name)
{
    global $contents_dir;
    // print_msg("parse_menu($file_name)");
    
    $fin = fopen($file_name, "r");
    if (!$fin) {
        echo "Unable to open '$file_name' in parse_menu($file_name)<br />";
        return;
    }
    
    echo "Opened '$file_name' in parse_menu($file_name)<br />";
    // Parse menu' specifications
    $count = 0;
    while (!feof($fin)) {
        $line = fgets($fin);
        if (feof($fin))
            break;
        $line = trim($line);
        if (strlen($line) == 0)
            continue;
        if ($line[0] == '#')
            continue;
        $articles[$count] = $line;
        $count++;
    }
    
    echo "Menu $file_name contains $count voices.<br>";
    
    fclose($fin);
    
    // print_msg("Found ".count($articles)." articles");
    // print_msg("Extracting info:");
    
    // Extracts info from files or menu' specifications
    foreach ($articles as $article) {
        // echo "<b>'".htmlspecialchars($article)."'</b><br />";
        if ($article[0] == ">") {
            // these are not translated from php to html
            $article = substr($article, 1);
            list($link, $title, $description) = explode(">", $article);
            $link        = trim($link);
            $description = trim($description);
            $title       = trim($title);
            htmlspecialchars($article);
            // echo "'$link' : '$title' : '$description'<br />";
            $headerinfo["description"] = $description;
            $headerinfo["title"]       = $title;
            if (isset($headers[$link])) {
                echo "<h2>ERROR: The article '$link' is already present.</h2>";
                return;
            }
            $headers[$link] = $headerinfo;
        } else if ($article[0] == "$") {
            // simply skip $
            $link                      = "<!--$-->" . trim(substr($article, 1));
            $headerinfo["description"] = "[HTML LONG]";
            $headerinfo["title"]       = "[HTML LONG]";
            if (isset($headers[$link]))
                die("Double [HTML LONG]!");
            $headers[$link] = $headerinfo;
        } else if ($article[0] == "&") {
            // simply skip $
            $link                      = "<!--&-->" . trim(substr($article, 1));
            $headerinfo["description"] = "[HTML SHORT]";
            $headerinfo["title"]       = "[HTML SHORT]";
            if (isset($headers[$link]))
                die("Double [HTML SHORT]!");
            $headers[$link] = $headerinfo;
        } else {
            $headerinfo = extract_header_info("$contents_dir/" . $article);
            if (!isset($headerinfo)) {
                echo "<h2>ERROR EXTRACTING HEADERS FOR '$article' </h2>";
                return false;
            }
            // print_header_info( $headerinfo );
            if (isset($headers[$article])) {
                echo "<h2>ERROR: The article '$article' is already present.</h2>";
                return;
            }
            
            $headers[$article] = $headerinfo;
        }
    }
    
    return $headers;
}

function generate_prev_next_map()
{
    global $cfg_dir;
    
    if (file_exists(("$cfg_dir/sidemenu.cfg")) == false) {
        echo "Missing CFG file, skipping.<br>";
        return;
    }
    
    $headers = parse_menu("$cfg_dir/sidemenu.cfg");
    
    if (!$headers)
        return;
    
    $prevnext = array();
    // A,B,C,D,E
    // A(*.B),B(A.C),C(B.D),D(C.E),E(D.*)
    // compile next/prev table
    foreach ($headers as $key => $info) {
        if (isset($prev)) {
            $prevnext[$key]['prev']  = $prev;
            $prevnext[$prev]['next'] = $key;
        }
        $prev = $key;
    }
    
    global $contents_dir;
    $fout = fopen("$contents_dir/prev_next_map.php", "w");
    fwrite($fout, "<?php\n");
    fwrite($fout, "// Function generated by generate_prev_next_map()\n");
    fwrite($fout, "function prev_next_map(\$filename) {\n");
    fwrite($fout, "switch(\$filename) {\n");
    foreach ($prevnext as $key => $siblings) {
        fwrite($fout, "case \"$key\":{\n");
        if (isset($siblings['prev']))
            fwrite($fout, "\$siblings['prev']=\"" . $siblings['prev'] . "\";\n");
        if (isset($siblings['next']))
            fwrite($fout, "\$siblings['next']=\"" . $siblings['next'] . "\";\n");
        fwrite($fout, "return \$siblings;\n");
        fwrite($fout, "break;\n}\n");
    }
    fwrite($fout, "default:\nreturn FALSE;\n");
    fwrite($fout, "}\n}\n?" . ">\n");
    fclose($fout);
}

function parse_questions($file_name)
{
    print_msg("parse_questions($file_name)");
    
    $fin = fopen($file_name, "r");
    if (!$fin) {
        echo "Unable to open '$file_name' in parse_questions($file_name)<br />";
        return;
    }
    
    $count        = 0;
    $new_question = true;
    $answer       = "";
    while (!feof($fin)) {
        $line = fgets($fin);
        
        if (feof($fin))
            break;
        
        if ($new_question == true)
            $line = trim($line);
        
        if (strlen($line) == 0)
            continue;
        
        // ends the answer
        if ($line[0] == '#') {
            // echo "A: [$count] = '$answer'<br />";
            $new_question      = true;
            $questions[$count] = $answer;
            $count++;
            continue;
        }
        
        // adds the question
        if ($new_question == true) {
            // echo "Q: [$count] = '$line'<br />";
            $new_question      = false;
            $questions[$count] = $line;
            $count++;
            $answer = "";
        } else {
            $answer = $answer . $line;
        }
    }
    
    fclose($fin);
    
    print_msg("Found " . (count($questions) / 2) . " questions");
    
    // must be a set pairs of questions and answers
    if (count($questions) % 2 == 0)
        return $questions;
    else {
        print_msg("<h1>ERROR in parse_questions: questions ad answers don't match!</h1>");
        return;
    }
}

// creates links.html and links_footer.html
function generate_links()
{
    global $cfg_dir;
    
    print_msg("<font color=blue>GENERATING LINKS</font>");
    
    if (file_exists(("$cfg_dir/links.cfg")) == false) {
        echo "Missing CFG file $cfg_dir/links.cfg, skipping.<br>";
        return;
    }
    
    // Parse side menu
    $linklist = parse_menu("$cfg_dir/links.cfg");
    // Write side menu voices grouped together (excluding HOME) con Descrizione
    if (!$linklist) {
        print_msg("error reading links.cfg");
        return;
    }
    
    global $contents_dir;
    $file_name = "$contents_dir/links.php";
    $fout      = fopen($file_name, "w");
    if (!$fout) {
        print_msg("Unable to write to '$file_name' in generate_links()");
        return false;
    }
    
    global $website_title;
    global $links_page_description;
    
    /* fwrite($fout, "<!--
    title: $website_title Links
    author: Michele Bosi
    date: ".date("j-n-Y")."
    description: $links_page_description
    keywords: flight, airfare, cheap, vacation, holiday
    template: default
    -->\n");
    
    fwrite($fout, "<center><h1>$website_title Links</h1></center>\n"); */
    
    fwrite($fout, "<dl>");
    foreach ($linklist as $key => $info) {
        if ($info["title"] == "HOME")
            continue;
        
        $link_desc  = $info["description"];
        $link_title = $info["title"];
        
        // echo "<li>".htmlspecialchars($link_title)." +======> $key<br>\n";
        
        if ($link_title == "[HTML SHORT]") {
            continue;
        } else if ($link_title == "[HTML LONG]") {
            // Direct HTML code
            fwrite($fout, "<div>$key</div><br>\n");
        } else {
            if (strpos($key, "http://") === 0) {
                // Absolute
                $link_href = $key;
            } else {
                // Relative
                $link_href = encode_href_doc($key);
            }
            
            // If it's an image
            if (strpos(strtolower($link_title), "img:") === 0) {
                $link_title = substr($link_title, 4);
                $link_title = "<img border=0 alt=\"$link_desc\" src=\"$link_title\">";
            }
            
            fwrite($fout, "<div><dt><a title=\"$link_desc\" href=\"$link_href\">$link_title</a></dt>");
            fwrite($fout, "<dd>$link_desc</dd></div><br>\n");
        }
    }
    fwrite($fout, "</dl>");
    fclose($fout);
    
    global $contents_dir;
    $file_name = "$contents_dir/links_footer.php";
    $fout      = fopen($file_name, "w");
    if (!$fout) {
        print_msg("Unable to write to '$file_name' in generate_links()");
        return false;
    }
    
    $count = 0;
    foreach ($linklist as $key => $info) {
        $count++;
        if ($info["title"] == "HOME")
            continue;
        
        $link_desc  = $info["description"];
        $link_title = $info["title"];
        if ($link_title == "[HTML LONG]") {
            continue;
        } else if ($link_title == "[HTML SHORT]") {
            // Direct HTML code
            fwrite($fout, "$key");
        } else {
            if (strpos($key, "http://") === 0) {
                // Absolute
                $link_href = $key;
            } else {
                // Relative
                $link_href = encode_href_doc($key);
            }
            
            // If it's an image
            if (strpos(strtolower($link_title), "img:") === 0) {
                $link_title = substr($link_title, 4);
                $link_title = "<img border=0 alt=\"$link_desc\" src=\"$link_title\">";
            }
            
            fwrite($fout, "<a title=\"$link_desc\" href=\"$link_href\">");
            fwrite($fout, $link_title);
            fwrite($fout, "</a>");
        }
        if (count($linklist) != $count) {
            fwrite($fout, " - ");
        }
        fwrite($fout, "\n");
    }
    fclose($fout);
    
}

function generate_googlesitemap()
{
    print_msg("<font color=blue>GENERATING GOOGLE SITEMAP</font>");
}

// FORMAT:
// pagetitle > question
// answer
// answer continued
// #--------------------
function generate_faq()
{
    print_msg("<font color=blue>GENERATING FAQ</font>");
    
    global $cfg_dir;
    
    if (file_exists(("$cfg_dir/faq.cfg")) == false) {
        echo "Missing CFG file, skipping.<br>";
        return;
    }
    
    $faq = parse_questions("$cfg_dir/faq.cfg");
    if (!$faq)
        return;
    
    global $contents_dir;
    $fout_index = fopen("$contents_dir/faq/index.php", "w");
    if (!$fout_index) {
        print_msg("Non posso scrivere sul file $fout_index!");
        return;
    }
    
    global $website_title;
    global $faq_page_description;
    
    // Print fout_index header informations
    fwrite($fout_index, "<!--
title: $website_title Frequently Asked Questions
author: Michele Bosi
date: " . date("j-n-Y") . "
description: $faq_page_description
keywords: flight, airfare, cheap, vacation, holiday
template: default
-->\n");
    
    fwrite($fout_index, "<center><h2>$website_title</h2>\n");
    fwrite($fout_index, "<h1>Frequently Asked Questions</h1></center>\n");
    fwrite($fout_index, "<?php require(\"$contents_dir/ad_combosquare.php\") ?" . ">\n");
    fwrite($fout_index, "<dl>\n");
    for ($count = 0; $count < count($faq); $count += 2) {
        list($filename, $question) = explode(">", $faq[$count]);
        
        $filename = strtolower(trim($filename));
        
        $question = trim($question);
        
        $answer = $faq[$count + 1];
        
        // echo "[$filename] $question<br />";
        
        // echo "$answer";
        
        if (isset($files[$filename])) {
            print_msg("ERROR: page '$filename' already used!");
            fclose($fout_index);
            return;
        } else
            $files[$filename] = true;
        
        if (isset($questions[$question])) {
            print_msg("ERROR: question '$question' already done!");
            fclose($fout_index);
            return;
        } else
            $questions[$question] = true;
        
        // echo "<hr />";
        
        // create the file for the faq
        // ...
        fwrite($fout_index, "<h2 class=\"faq_index_question\">Q" . (($count + 2) / 2) . ". <a href=\"" . encode_href_doc("faq/" . $filename . ".html") . "\" title=\"$question\"><dt>$question</dt></a></h2>\n");
        
        global $faq_answer_page_description;
        
        // update the faq index
        global $contents_dir;
        $fout_faq = fopen("$contents_dir/faq/$filename.php", "w");
        fwrite($fout_faq, "<!--
title: FAQ - " . $question . "
author: Michele Bosi
date: " . date("j-n-Y") . "
description: $faq_answer_page_description
keywords: flight, airfare, cheap, vacation, holiday
template: default
-->\n");
        fwrite($fout_faq, "<center><h1><a href=\"" . encode_href_doc("faq/index.html") . "\" title=\"Back To $website_title FAQs\">$website_title FAQs</a></h1></center>\n");
        fwrite($fout_faq, "<dl><dt><h2>Q" . (($count + 2) / 2) . ". ");
        fwrite($fout_faq, "$question</h2></dt>\n");
        // fwrite($fout_faq, "<font color=red>IN MEZZO CI VA LA PUBBLICITA'</font>\n");
        fwrite($fout_faq, "<center><?php require(\"$contents_dir/ad_leaderboard.php\") ?" . "></center>\n");
        fwrite($fout_faq, "<h2>Answer:</h2><dd>$answer</dd></dl>\n");
        fwrite($fout_faq, "<center><?php require(\"$contents_dir/ad_leaderboard.php\") ?" . "></center>\n");
        fwrite($fout_faq, "<br />\n");
        fclose($fout_faq);
    }
    
    fwrite($fout_index, "</dl>\n");
    fclose($fout_index);
}

function generate_glossary()
{
    print_msg("<font color=blue>GENERATING GLOSSARY</font>");
    
    global $cfg_dir;
    
    if (file_exists(("$cfg_dir/glossary.cfg")) == false) {
        echo "Missing CFG file, skipping.<br>";
        return;
    }
    
    $glossary = parse_questions("$cfg_dir/glossary.cfg");
    if (!$glossary)
        return;
    
    global $contents_dir;
    $fout_index = fopen("$contents_dir/glossary/index.php", "w");
    if (!$fout_index) {
        print_msg("Non posso scrivere sul file $fout_index!");
        return;
    }
    
    global $website_title;
    global $glossary_page_description;
    
    // Print fout_index header informations
    fwrite($fout_index, "<!--
title: $website_title Glossary
author: Michele Bosi
date: " . date("j-n-Y") . "
description: $glossary_page_description
keywords: flight, airfare, cheap, vacation, holiday
template: default
-->\n");
    
    fwrite($fout_index, "<center><h1>$website_title Glossary</h1></center>");
    
    // Compile alphabetical list.
    for ($count = 0; $count < count($glossary); $count += 2) {
        $word["question"] = trim($glossary[$count]);
        $word["answer"]   = $glossary[$count + 1];
        
        if (!isset($alpha_list[strtolower($word["question"][0])]))
            $alpha_list[strtolower($word["question"][0])] = array();
        
        array_push($alpha_list[strtolower($word["question"][0])], $word);
    }
    
    // List alphabetically:
    
    // Sorts word groups
    ksort($alpha_list);
    
    fwrite($fout_index, "<center><h2>");
    foreach ($alpha_list as $key => $words) {
        fwrite($fout_index, "<a href=\"#" . strtoupper($key) . "\">" . strtoupper($key) . "</a> ");
    }
    fwrite($fout_index, "</h2></center>");
    
    fwrite($fout_index, "<?php require(\"$contents_dir/ad_combosquare.php\") ?" . ">\n");
    
    foreach ($alpha_list as $key => $words) {
        fwrite($fout_index, "<a name=\"" . strtoupper($key) . "\"></a>\n");
        fwrite($fout_index, "<h1>" . strtoupper($key) . "</h1>\n");
        fwrite($fout_index, "<dl>\n");
        
        // sorts words
        asort($words);
        
        $divider = false;
        foreach ($words as $word) {
            $filename = transform_str_in_filename(strtolower($word["question"]));
            
            // TODO: check for duplicates
            if (isset($filenames[$filename])) {
                print_msg("ERROR: page '$filename' already used!");
                fclose($fout_index);
                return;
            } else
                $filenames[$filename] = true;
            
            global $contents_dir;
            $fout_glossary = fopen("$contents_dir/glossary/" . $filename . ".php", "w");
            if (!$fout_glossary) {
                print_msg("ERROR: cannot write glossary file: $fout_glossary");
                return;
            }
            
            global $glossary_answer_page_description;
            
            fwrite($fout_glossary, "<!--
title: Glossary - " . $word["question"] . "
author: Michele Bosi
date: " . date("j-n-Y") . "
description: $glossary_answer_page_description
keywords: flight, airfare, cheap, vacation, holiday
template: default
-->\n");
            
            fwrite($fout_glossary, "<dl><center><h1><a href=\"" . encode_href_doc("glossary/index.html") . "\" title=\"$website_title Glossary\">Glossary</a>: <dt>" . $word["question"] . "</dt></h1></center>\n");
            fwrite($fout_glossary, "<center><?php require(\"$contents_dir/ad_leaderboard.php\") ?" . "></center>\n");
            fwrite($fout_glossary, "<h2>Definition:</h2>\n");
            fwrite($fout_glossary, "<dd>" . $word["answer"] . "</dd></dl>\n");
            fwrite($fout_glossary, "<center><?php require(\"$contents_dir/ad_leaderboard.php\") ?" . "></center>\n");
            fwrite($fout_glossary, "<br />\n");
            
            fclose($fout_glossary);
            
            if ($divider) {
                fwrite($fout_index, " - \n");
            }
            fwrite($fout_index, "<span class=\"glossary_word\"><a href=\"" . encode_href_doc("glossary/" . $filename . ".html") . "\" title=\"" . $word["question"] . "\"> </dt>" . $word["question"] . "</dt></a></span>\n");
            $divider = true;
        }
        fwrite($fout_index, "</dl>\n");
    }
    
    fwrite($fout_index, "<center><h2>");
    foreach ($alpha_list as $key => $words) {
        fwrite($fout_index, "<a href=\"#" . strtoupper($key) . "\">" . strtoupper($key) . "</a> ");
    }
    fwrite($fout_index, "</h2></center>");
    
    fclose($fout_index);
}

function generate_rss()
{
    print_msg("<font color=blue>GENERATING RSS</font>");
    
    $file_name = "rssfeeds.xml";
    
    global $cfg_dir;
    
    if (file_exists(("$cfg_dir/sidemenu.cfg")) == false) {
        echo "Missing CFG file, skipping.<br>";
        return;
    }
    
    $fout = fopen($file_name, "w");
    if (!$fout) {
        print_msg("Unable to write to '$file_name' in generate_rss()");
        return false;
    }
    
    // Parse side menu
    $sidemenu = parse_menu("$cfg_dir/sidemenu.cfg");
    // Write side menu voices grouped together (excluding HOME) con Descrizione
    if (!$sidemenu) {
        print_msg("error reading sidemenu.cfg");
        return;
    }
    
    global $website_title;
    global $rss_description;
    
    $category = "Articles";
    $author   = "$website_title Crew";
    global $base_website_address;
    
    fwrite($fout, '<rss version="2.0">
  <channel>
    <title>' . $website_title . '</title>
    <link>' . htmlspecialchars($base_website_address) . '</link>
    <description>' . $rss_description . '</description>
    <category>Articles</category>
    <generator>Robot</generator>
    <webMaster>none</webMaster>
');
    
    foreach ($sidemenu as $key => $info) {
        if ($info["title"] == "HOME")
            continue;
        
        $link_desc  = $info["description"];
        $link_title = $info["title"];
        if (strpos($key, "http://") === 0) {
            // Absolute
            $link_href = $key;
        } else {
            // Relative
            $link_href = encode_href_doc($key);
        }
        
        fwrite($fout, '    <item>
      <title>' . htmlspecialchars($link_title) . '</title>
      <link>' . htmlspecialchars($link_href) . '</link>
      <description>' . htmlspecialchars($link_desc) . '</description>
      <author>' . htmlspecialchars($author) . '</author>
      <category>' . htmlspecialchars($category) . '</category>
    </item>
');
    }
    
    fwrite($fout, '  </channel>
</rss>
');
    
    fclose($fout);
}

function generate_sitemap()
{
    print_msg("<font color=blue>GENERATING SITEMAP</font>");
    
    global $contents_dir;
    $file_name = "$contents_dir/sitemap.php";
    $fout      = fopen($file_name, "w");
    if (!$fout) {
        print_msg("Unable to write to '$file_name' in generate_main_menu()");
        return false;
    }
    
    // Parse horz menu
    global $cfg_dir;
    
    if (file_exists(("$cfg_dir/horzmenu.cfg")) == false) {
        echo "Missing CFG file, skipping.<br>";
        return;
    }
    
    if (file_exists(("$cfg_dir/sidemenu.cfg")) == false) {
        echo "Missing CFG file, skipping.<br>";
        return;
    }
    
    $horzmenu = parse_menu("$cfg_dir/horzmenu.cfg");
    if (!$horzmenu) {
        print_msg("error reading horzmenu.cfg");
        return;
    }
    
    global $website_title;
    global $sitemap_page_description;
    
    fwrite($fout, "<!--
title: $website_title Sitemap
author: Michele Bosi
date: " . date("j-n-Y") . "
description: $sitemap_page_description
keywords: flight, airfare, cheap, vacation, holiday
template: default
-->\n");
    
    fwrite($fout, "<center><h1>$website_title Sitemap</h1></center>\n");
    
    fwrite($fout, "<h2>TOP MENU</h2>\n");
    
    fwrite($fout, "<dl>\n");
    foreach ($horzmenu as $key => $info) {
        $link_desc  = $info["description"];
        $link_title = $info["title"];
        
        if (strpos($key, "http://") === 0) {
            // Absolute
            $link_href = $key;
        } else {
            // Relative
            $link_href = encode_href_doc($key);
        }
        
        fwrite($fout, "<dt><a title=\"$link_desc\" href=\"$link_href\">$link_title</a></dt>\n");
        fwrite($fout, "<dd>$link_desc</dd>\n");
    }
    fwrite($fout, "</dl>\n");
    
    // Write horz menu voices grouped together (excluding HOME) con Descrizione
    
    // Parse side menu
    global $cfg_dir;
    $sidemenu = parse_menu("$cfg_dir/sidemenu.cfg");
    // Write side menu voices grouped together (excluding HOME) con Descrizione
    if (!$sidemenu) {
        print_msg("error reading sidemenu.cfg");
        return;
    }
    
    fwrite($fout, "<h2>ARTICLES</h2>\n");
    
    fwrite($fout, "<dl>\n");
    foreach ($sidemenu as $key => $info) {
        if ($info["title"] == "HOME")
            continue;
        
        $link_desc  = $info["description"];
        $link_title = $info["title"];
        
        if (strpos($key, "http://") === 0) {
            // Absolute
            $link_href = $key;
        } else {
            // Relative
            $link_href = encode_href_doc($key);
        }
        
        fwrite($fout, "<dt><a title=\"$link_desc\" href=\"$link_href\">$link_title</a></dt>\n");
        fwrite($fout, "<dd>$link_desc</dd>\n");
    }
    fwrite($fout, "</dl>\n");
    
    fclose($fout);
}

// Genera anche prev_next_map
function generate_side_menu()
{
    global $cfg_dir;
    
    print_msg("<font color=blue>GENERATING SIDE MENU</font>");
    
    if (file_exists(("$cfg_dir/sidemenu.cfg")) == false) {
        echo "Missing CFG file, skipping.<br>";
        return;
    }
    
    generate_prev_next_map();
    
    $headers = parse_menu("$cfg_dir/sidemenu.cfg");
    
    if (!$headers)
        return;
    
    global $contents_dir;
    $file_name = "$contents_dir/sidemenu.php";
    $fout      = fopen($file_name, "w");
    if (!$fout) {
        echo "Unable to write to '$file_name' in generate_main_menu()<br />";
        return false;
    }
    
    fwrite($fout, "<table border=1 cellspacing=5 cellpadding=5>\n");
    
    foreach ($headers as $key => $info) {
        $link_desc  = $info["description"];
        $link_title = $info["title"];
        if (strpos($key, "http://") === 0) {
            // Absolute
            $link_href = $key;
        } else {
            // Relative
            $link_href = encode_href_doc($key);
        }
        
        fwrite($fout, "  <tr><td>\n");
        fwrite($fout, "    <a title=\"$link_desc\" href=\"$link_href\">");
        fwrite($fout, $link_title);
        fwrite($fout, "</a>\n");
        fwrite($fout, "  </td></tr>\n");
    }
    
    fwrite($fout, "</table>\n");
    
    fclose($fout);
    
    return true;
}

function generate_horz_menu()
{
    print_msg("<font color=blue>GENERATING HORZ MENU</font>");
    
    global $cfg_dir;
    
    if (file_exists(("$cfg_dir/horzmenu.cfg")) == false) {
        echo "Missing CFG file, skipping.<br>";
        return;
    }
    
    $headers = parse_menu("$cfg_dir/horzmenu.cfg");
    
    if (!$headers)
        return;
    
    // Generates the main menu'
    global $contents_dir;
    $file_name = "$contents_dir/horzmenu.php";
    $fout      = fopen($file_name, "w");
    if (!$fout) {
        echo "Unable to write to '$file_name' in generate_main_menu()<br />";
        return false;
    }
    
    fwrite($fout, "<table border=1 cellspacing=5 cellpadding=5>\n");
    fwrite($fout, "<tr>\n");
    
    foreach ($headers as $key => $info) {
        $link_desc  = $info["description"];
        $link_title = $info["title"];
        if (strpos($key, "http://") === 0) {
            // Absolute
            $link_href = $key;
        } else {
            // Relative
            $link_href = encode_href_doc($key);
        }
        
        fwrite($fout, "  <td>\n");
        fwrite($fout, "    <a title=\"$link_desc\" href=\"$link_href\">");
        fwrite($fout, $link_title);
        fwrite($fout, "</a>\n");
        fwrite($fout, "  </td>\n");
    }
    
    fwrite($fout, "</€tr>\n");
    fwrite($fout, "</table>\n");
    
    fclose($fout);
    
    return true;
}

// $file_name is translated from .html -> .php
function extract_header_info($file_name)
{
    if (strpos(strtolower($file_name), ".html") == strlen($file_name) - 5)
        $file_name = substr($file_name, 0, strlen($file_name) - 5) . ".php";
    
    $fin = fopen($file_name, "r");
    if (!$fin) {
        echo "Unable to open file for reading: $file_name<br />";
        return FALSE;
    }
    
    $linecount = 0;
    while (!feof($fin)) {
        $linea = fgets($fin);
        // echo "[$linecount] ".htmlspecialchars($linea)." <br />";
        if (feof($fin)) {
            // echo "EOF<br />";
            break;
        }
        $linecount++;
        $linea = trim($linea);
        
        if (($linecount == 1) && ($linea != '<!--')) {
            echo "<h1 style=\"color:red\"><br />FILE '$file_name' DOESN'T HAVE AN HEADER!</h1>";
            return FALSE;
        }
        
        if ($linea == '<!--')
            continue;
        if ($linea == '-->') {
            // echo "FINE<br />";
            break;
        }
        
        /* $pieces = explode(":", $linea);
        $tag = $pieces[0];
        $value = $pieces[1]; */
        
        $divider_pos = strpos($linea, ":");
        $tag         = substr($linea, 0, $divider_pos);
        $value       = substr($linea, $divider_pos + 1);
        
        $tag   = strtolower(trim($tag));
        $value = trim($value);
        
        // echo "'$tag' = '$value'<br>";
        
        if ($tag == 'author') {
            // echo "autore: '$value' <br />";
            $header['author'] = $value;
        } else if ($tag == 'title') {
            // echo "titolo: '$value' <br />";
            $header['title'] = $value;
        } else if ($tag == 'date') {
            // echo "data: '$value' <br />";
            $header['date'] = $value;
        } else if ($tag == 'description') {
            // echo "descrizione: '$value' <br />";
            $header['description'] = $value;
        } else if ($tag == 'keywords') {
            // echo "keywords: '$value' <br />";
            $header['keywords'] = $value;
        } else if ($tag == 'template') {
            // echo "template: '$value' <br />";
            $header['template'] = $value;
        } else {
            echo "ERROR unknown directive \"" . htmlspecialchars($linea) . "\" at line $linecount in file \"$file_name\".<br />";
        }
    }
    
    if (!isset($header['keywords']))
        $header['keywords'] = "none";
    
    if (!isset($header['author']))
        $header['author'] = "none";
    
    if (!isset($header['description'])) {
        $header['description'] = "none";
        echo "<h1>This page doesn't have a description!</h1>";
    }
    
    if (!isset($header['title'])) {
        $header['title'] = "none";
        echo "<h1>This page doesn't have a title!</h1>";
    }
    
    fclose($fin);
    
    return $header;
}

function print_header_info($info)
{
    echo "titolo: " . $info["title"] . " <br />";
    echo "descrizione: " . $info["description"] . " <br />";
    echo "autore: " . $info["author"] . " <br />";
    echo "data: " . $info["date"] . " <br />";
    echo "keywords: " . $info["keywords"] . " <br />";
    echo "template: " . $info["template"] . " <br />";
}

function generate_tablature()
{
    print_msg("<font color=blue>GENERATING TABLATURE</font>");
    
    global $spartiti_dir;
    global $cfg_dir;
    
    if (file_exists(("$cfg_dir/tablature.cfg")) == false) {
        echo "Missing CFG file, skipping.<br>";
        return;
    }
    
    global $contents_dir;
    $file_name = "$cfg_dir/tablature.cfg";
    $fin       = fopen($file_name, "r");
    if (!$fin) {
        print_msg("Unable to open '$file_name' in generate_tablature()");
        return false;
    }
    
    $author      = null;
    $album       = null;
    $track       = null;
    $year        = null;
    $fout        = null;
    $filename    = null;
    $artist_list = null;
    $track_num   = 0;
    $linenum     = 0;
    $artistcount = 0;
    $songcount   = 0;
    while (true) {
        $linenum++;
        $line = fgets($fin);
        
        global $base_website_address;
        
        if (feof($fin)) {
            if ($fout != null) {
                fwrite($fout, "<h2><a href=\"http://<?php echo \"\$base_website_address/\$spartiti_dir\" ?" . ">/index.html\" title=\"Trova accordi, spartiti, tablature e testi di tantissimi artisti\">Torna all'indice degli artisti</a></h2>\n");
                fclose($fout);
            }
            break;
        }
        
        $line = trim($line);
        
        if (strlen($line) == 0)
            continue;
        
        if ($line[0] == '#')
            continue;
        
        $divider_pos = strpos($line, ":");
        $tag         = substr($line, 0, $divider_pos);
        $value       = substr($line, $divider_pos + 1);
        $tag         = trim($tag);
        $value       = trim($value);
        
        if ($value == "") {
            echo "ERROR: no value at line $linenum<br>";
            return;
        }
        
        // echo "$tag == $value<br>";
        
        switch (strtolower($tag)) {
            case "artist": {
                $artistcount++;
                $author = $value;
                // reset variables
                $album  = null;
                $track  = null;
                $year   = null;
                // close previous artist
                if ($fout != null) {
                    fwrite($fout, "<h2><a href=\"http://<?php echo \"\$base_website_address/\$spartiti_dir\" ?" . ">/index.html\" title=\"Trova accordi, spartiti, tablature e testi di tantissimi artisti\">Torna all'indice degli artisti</a></h2>\n");
                    fclose($fout);
                }
                // creates filename for this artist
                $filename            = "spartiti-chitarra-testi-accordi-" . strtolower(transform_str_in_filename($value));
                // compiles the list for the index
                $artist_list[$value] = $filename;
                // opens the file for this artist
                $fout                = fopen($contents_dir . "/$spartiti_dir/" . $filename . ".php", "w");
                // write header
                fwrite($fout, "<!--\n");
                fwrite($fout, "title: Discografia $author. Spartiti, accordi, testi e tablature di $author\n");
                fwrite($fout, "description: Discografia $author. Trova tutti gli spartiti, accordi, testi e tablature di $author\n");
                fwrite($fout, "keywords: Discografia, $author, spartiti, accordi, testi, tablature\n");
                fwrite($fout, "template: tablature\n");
                fwrite($fout, "-->\n");
                fwrite($fout, "<h1>Discografia $author</h1><h2>Trova spartiti, accordi, testi e tablature</h2>\n");
                fwrite($fout, "<?php require(\"tabdescription.php\"); ?" . ">\n");
                fwrite($fout, "<?php require(\"adsense_leaderboard.php\"); ?" . ">\n");
                break;
            }
            case "album": {
                $track_num = 1;
                fwrite($fout, "<h2>$value ");
                if ($year != null)
                    fwrite($fout, "($year)");
                fwrite($fout, "</h2>\n");
                $album = $value;
                break;
            }
            case "year": {
                $year = $value;
                break;
            }
            case "track": {
                $songcount++;
                $url_track  = urlencode($value);
                $ulr_author = urlencode($author);
                fwrite($fout, "<b>$track_num</b>. $value - ");
                fwrite($fout, "<a target=\"_blank\" href=\"http://www.google.com/search?q=spartiti+$url_track+$ulr_author\" title=\"Spartiti di $value\">spartiti</a>");
                fwrite($fout, ", <a target=\"_blank\" href=\"http://www.google.com/search?q=accordi+$url_track+$ulr_author\" title=\"Accordi di $value\">accordi</a>");
                fwrite($fout, ", <a target=\"_blank\" href=\"http://www.google.com/search?q=testi+$url_track+$ulr_author\" title=\"Testi di $value\">testi</a>");
                // fwrite($fout, ", <a target=\"_blank\" href=\"http://www.google.com/search?q=tablature+$url_track+$ulr_author\" title=\"cerca tablature di '$value'\">tablature</a>");
                // fwrite($fout, ", <a target=\"_blank\" href=\"http://www.google.com/search?q=lyrics+$url_track+$ulr_author\" title=\"cerca lyrics di '$value'\">lyrics</a>");
                // fwrite($fout, ", <a target=\"_blank\" href=\"http://www.google.com/search?q=chords+$url_track+$ulr_author\" title=\"cerca chords di '$value'\">chords</a>");
                // fwrite($fout, ", <a target=\"_blank\" href=\"http://www.google.com/search?q=tabs+$url_track+$ulr_author\" title=\"cerca tabs di '$value'\">tabs</a>");
                // fwrite($fout, ", <a target=\"_blank\" href=\"http://www.google.com/search?q=powertabs+$url_track+$ulr_author\" title=\"cerca powertabs di '$value'\">powertabs</a>");
                fwrite($fout, "<br>\n");
                $track_num++;
                break;
            }
            default: {
                echo "ERROR: unknown tag '$tag' at line $linenum<br>\n";
                return false;
                break;
            }
        }
    }
    
    fclose($fin);
    
    // alphabetical sort by artists
    ksort($artist_list);
    
    // generate index
    /* $fout = fopen($contents_dir."/$spartiti_dir/index.php", "w");
    fwrite($fout, "<!--\n");
    fwrite($fout, "title: Trova discografie, spartiti, accordi, testi e tablature di artisti italiani e stranieri\n");
    fwrite($fout, "description: Trova discografie, spartiti, accordi, testi e tablature di artisti italiani e stranieri.\n");
    fwrite($fout, "keywords: Discografia, spartiti, accordi, testi, tablature\n");
    fwrite($fout, "template: default\n");
    fwrite($fout, "-->\n");
    fwrite($fout, "<center><h1>Trova discografie, spartiti, accordi, testi e tablature di $artistcount artisti italiani e stranieri e $songcount canzoni!</h1></center>\n");
    
    fwrite($fout, "<p>Quante volte ci e' capitato di cercare uno spartito, un testo o gli accordi di una canzone, o di voler conoscere la discografia di un artista?
    Sicuramente tantissime! Questo servizo di permette di utilizzare al meglio la potenza di Google in modo semplice ed intuitivo per trovare i migliori spartiti, accordi e testi delle tue canzoni preferite.
    Clicca sul nome di un artista per vedere la sua discografia, ti si presentera' la lista delle relative canzoni divise per album con accanto dei pratici link
    che ti porteranno dritto alle fonti piu' accreditate da cui reperire i migliori spartiti, accordi, testi e tablature che ti interessano.
    Spesso per trovare
    degli spartiti o accordi di adeguata qualita' e' necessario verificare piu' fonti, per cui e' preferibile controllare almeno due o tre versioni della stessa canzone da siti diversi.\n
    Nel database sono presenti ben $songcount canzoni di $artistcount artisti!</p>
    <p>Buona ricerca!</p>\n");
    
    fwrite($fout, "<?php require(\"tabgrid.php\"); ?".">\n");
    fclose($fout); */
    
    $count = 0;
    $col   = 1;
    $incol = 0;
    $fout  = fopen($contents_dir . "/$spartiti_dir/tabgrid.php", "w");
    fwrite($fout, "<table class=\"tabindex\" border=0><tr><td valign=top>\n");
    foreach ($artist_list as $artist => $file) {
        $count++;
        $incol++;
        fwrite($fout, "<b>$count.</b> <a href=\"http://<?php echo \"\$base_website_address/\$spartiti_dir\" ?" . ">/$file.html\" title=\"Trova accordi, spartiti, tablature e testi di $artist\">$artist</a><br />\n");
        if ($incol == 10) {
            $incol = 0;
            $col++;
            fwrite($fout, "</td>\n");
            if ($col == 5) {
                fwrite($fout, "</tr><tr>\n");
                $col = 1;
            }
            fwrite($fout, "<td valign=top>\n");
        }
    }
    for ($i = 0; $i < 4 - $col; $i++)
        fwrite($fout, "</td><td>&nbsp;\n");
    fwrite($fout, "</td>\n");
    fwrite($fout, "</tr></table>\n");
    fclose($fout);
}

function receive_email($destination_mail, $fake_from_mail, $allowed_address)
{
    function has_newlines($text)
    {
        return preg_match("/(%0A|%0D|\n+|\r+)/i", strtolower($text)) != 0;
    }
    
    function has_emailheaders($text)
    {
        return preg_match("/(%0A|%0D|\\n+|\\r+)(content-type:|to:|cc:|bcc:)/i", strtolower($text)) != 0;
    }
    
    // Clean input in case of header injection attempts but makes email lowercase
    // and without breaks. NOT USED AS FOR NOW.
    function clean_injection_code($value)
    {
        return $value;
        /* $patterns[0] = '/content-type:/';
        $patterns[1] = '/to:/';
        $patterns[2] = '/cc:/';
        $patterns[3] = '/bcc:/';
        $patterns[4] = '/\r/';
        $patterns[5] = '/\n/';
        $patterns[6] = '/%0a/';
        $patterns[7] = '/%0d/';
        
        // str_ireplace is case insensitive but available only on PHP 5.0 and above
        return preg_replace($patterns, "", strtolower($value)); */
    }
    
    $success_msg = '<p align="center"><strong>Il tuo messaggio è stato inviato con successo.</strong><br>
   <p align="center">Una copia del messaggio è stata inviata all\'indirizzo specificato.</p>
   <p align="center">Grazie per averci contattato.</p>
   <p align="center"><a href="/index.html">Continua</a></p>';
    
    if (!isset($_POST['email'])) {
        die("<h1>#&^%$!@#(^&%#$% Error: Invalid checksum error 1 :(</h1>");
    }
    
    echo "<h2><i>Message sent</i></h2>";
    echo "<i>mail</i>: " . $_POST['email'] . "<br>";
    echo "<i>name</i>: " . htmlspecialchars($_POST['name']) . "<br>";
    echo "<i>subject</i>: " . htmlspecialchars($_POST['mailsubject']) . "<br>";
    echo "<i>message</i>: <pre>" . htmlspecialchars($_POST['messagebody']) . "</pre><br>";
    
    /* if($_SERVER["HTTP_REFERER"] != $allowed_address)
    {
    header("Location: $allowed_address");
    exit;
    } */
    
    // CLEAN INJECTION CODE
    
    if (!preg_match("/^[A-Z0-9._%-]+@[A-Z0-9.-]+.[A-Z]{2,4}$/i", $_POST["email"]))
        die("<h1>Error: Invalid email address.</h1>");
    
    if (has_newlines($_POST["name"]))
        die("<h1>#&^%$!@#(^&%#$% Error: Invalid checksum error 3 :(</h1>");
    
    if (has_newlines($_POST["email"]))
        die("<h1>#&^%$!@#(^&%#$% Error: Invalid checksum error 4 :(</h1>");
    
    if (has_newlines($_POST["mailsubject"]))
        die("<h1>#&^%$!@#(^&%#$% Error: Invalid checksum error 5 :(</h1>");
    
    if (has_emailheaders($_POST["name"]))
        die("<h1>#&^%$!@#(^&%#$% Error: Invalid checksum error 6 :(</h1>");
    
    if (has_emailheaders($_POST["email"]))
        die("<h1>#&^%$!@#(^&%#$% Error: Invalid checksum error 7 :(</h1>");
    
    if (has_emailheaders($_POST["mailsubject"]))
        die("<h1>#&^%$!@#(^&%#$% Error: Invalid checksum error 8 :(</h1>");
    
    if (has_emailheaders($_POST["messagebody"]))
        die("<h1>#&^%$!@#(^&%#$% Error: Invalid checksum error 9 :(</h1>");
    
    $name        = clean_injection_code($_POST["name"]);
    $email       = clean_injection_code($_POST["email"]);
    $mailsubject = clean_injection_code(stripcslashes($_POST["mailsubject"]));
    $messagebody = clean_injection_code(stripcslashes($_POST["messagebody"]));
    $messagebody = "Nome: $name \n\nMessaggio: $messagebody";
    
    $replymessage = "Ciao $name

  Grazie per averci inviato la tua mail,
  il nostro staff le rispondera' al piu' presto.

  Nota: NON rispondere a questa email.

  Di seguito e' riportata la copia del messaggio inviato:
  --------------------------------------------------
  Subject: $mailsubject
  Messaggio:
  $messagebody
  --------------------------------------------------

  Grazie!";
    
    $mailsent = mail("$destination_mail", "$mailsubject", "$messagebody", "From: $email\nReply-To: $email");
    
    $copysent = mail("$email", "Mail inviata: $mailsubject", "$replymessage", "From: $fake_from_mail\nReply-To: $fake_from_mail");
    
    if ($mailsent == TRUE)
        echo $success_msg;
    else
        die("<h2>Errore nell'invio della mail!</h2>");
    
    if ($copysent == FALSE)
        die("<h2>Errore nell'invio della copia!</h2>");
}

function send_mail_form($params)
{
    $testo_subject   = $params['subject'];
    $testo_messaggio = $params['message'];
    $receiver_script = $params['receiver_script'];
    $star_style      = $params['star_style'];
    $note_style      = $params['note_style'];
    $text_style      = $params['text_style'];
    $table_style     = $params['table_style'];
?>
<form name="mailform" align="center" method="post" action="<?php
    echo $receiver_script;
?>">
    <table style="<?php
    echo $table_style;
?>" width="550" cellspacing="5">
    
    <?php
    if ($params['show_name'] == TRUE) {
?>
    <tr>
      <td align="right">
        <span style="<?php
        echo $note_style;
?>">Nome</span>
      </td>
      <td>
        <input style="<?php
        echo $text_style;
?>" size="35" name="name" value="<?php
        echo $params['name'];
?>">
      </td>
    </tr>
    <?php
    }
?>
    
    <?php
    if ($params['show_email'] == TRUE) {
?>
    <tr>
      <td align="right">
        <span style="<?php
        echo $star_style;
?>">*</span><span style="<?php
        echo $note_style;
?>">e-mail</span>
      </td>
      <td align="left">
        <input style="<?php
        echo $text_style;
?>" size="35" name="email">
      </td>
    </tr>
    <tr align="middle">
      <td align="right">
        <span style="<?php
        echo $star_style;
?>">*</span><span style="<?php
        echo $note_style;
?>">Conferma e-mail</span>
      </td>
      <td align="left">
        <input style="<?php
        echo $text_style;
?>" size="35" name="confirmMail">
      </td>
    </tr>
    <?php
    }
?>

    <?php
    if ($testo_subject == "") {
?>
    <tr>
      <td align="right">
        <span style="<?php
        echo $star_style;
?>">*</span><span style="<?php
        echo $note_style;
?>">Titolo del messaggio</span>
      </td>
      <td>
        <input style="<?php
        echo $text_style;
?>" size="35" name="mailsubject" value="<?php
        echo $testo_subject;
?>">
      </td>
    </tr>
    <?php
    }
?>
    <tr>
      <td align="right" valign=top>
        <span style="<?php
    echo $star_style;
?>">*</span><span style="<?php
    echo $note_style;
?>">Messaggio</span>
        <?php
    if ($testo_subject != "")
        echo '<input type="hidden" name="mailsubject" value="' . $testo_subject . '">';
    if ($params['show_email'] == FALSE)
        echo '<input type="hidden" name="email" value="nomailspecified@nomailspecified.xyz">';
    if ($params['show_name'] == FALSE)
        echo '<input type="hidden" name="name" value="<NO NAME SPECIFIED>">';
?>
      </td>
      <td>
        <textarea style="<?php
    echo $text_style;
?>" name="messagebody" rows="9" cols="50"><?php
    echo $testo_messaggio;
?></textarea>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
        <script language="JavaScript">
        <!--
        function controlla()
        {
         with (document.mailform)
         {
          if (email.value.indexOf("@",0) <= 0)
          {
            alert ("Invalid email address: "+email.value)
            email.focus()
            return;
          }
          if ( email.value != confirmMail.value )
          {
            alert ("Email addresses do not match:\nmail1="+email.value+"\nmail2=" + confirmMail.value)
            email.focus()
            return;
          }
          if (mailsubject.value=="")
          {
            alert("You need to enter a subject!")
            mailsubject.focus()
            return;
          }
          if (messagebody.value=="")
          {
            alert("Cannot send an empty message!")
            messagebody.focus()
            return;
          }
          submit();
         }
        }
        // -->
        </script>
      <span style="<?php
    echo $note_style;
?>">I campi obbligatori sono contrassegnati da un </span><span style="<?php
    echo $star_style;
?>">*</span>
      <br>
      <input type="button" class="button" value="Invia" onclick="javascript:controlla()">
      </td>
    </tr>
  </table>
</form>

<?php
}
?>
