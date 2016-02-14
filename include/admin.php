<?php
  require("../config.php");
  require("core.php");
  # phpinfo();
?>

<li>Artisti mancanti: battisti, de andre, gino paoli, steve vai, bob marley, morrison, satriani, clapton, madonna, lenny kravitz, simply red, venditti, </li>
<li>OTTIMIZZA NOMI DI FILE E DIRECTORIES, TABULATI => SPARTITI-TESTI-Canzoni, file => spartiti-testi-discografia-nomegruppo.html</li>

<center>
<table border=0 cellspacing=5 cellpadding=5 width=100% style="border: 1px solid orange">
  <tr>
    <td valign=top align=center style="border: 1px solid green" width=200>
      <h2><a href="admin.php" style="color:red; text-decoration:none;" >MASTER PANEL</a></h2>
      <form action="admin.php" method="GET" >
        <input type="hidden" name="generate_all" value="1" />
        <input type="submit" value="Generate All" style="width: 100%" />
      </form>
      <hr />
      <form action="admin.php" method="GET" >
        <input type="hidden" name="generate_tablature" value="1" />
        <input type="submit" value="Generate Tablature" style="width: 100%" />
      </form>
      <hr />
      <form action="admin.php" method="GET" >
        <input type="hidden" name="generate_mainmenu" value="1" />
        <input type="submit" value="Generate Sidemenu" style="width: 100%" />
      </form>
      <form action="admin.php" method="GET">
        <input type="hidden" name="generate_horzmenu" value="1" />
        <input type="submit" value="Generate Horzmenu" style="width: 100%" />
      </form>
      <form action="admin.php" method="GET">
        <input type="hidden" name="generate_sitemap" value="1" />
        <input type="submit" value="Generate Sitemap" style="width: 100%" />
      </form>
      <form action="admin.php" method="GET">
        <input type="hidden" name="generate_rss" value="1" />
        <input type="submit" value="Generate RSS" style="width: 100%" />
      </form>
      <form action="admin.php" method="GET">
        <input type="hidden" name="generate_links" value="1" />
        <input type="submit" value="Generate Links" style="width: 100%" />
      </form>
      <form action="admin.php" method="GET">
        <input type="hidden" name="generate_googlesitemap" value="1" />
        <input type="submit" value="Generate Google Sitemap" style="width: 100%" />
      </form>
      <form action="admin.php" method="GET">
        <input type="hidden" name="generate_faq" value="1" />
        <input type="submit" value="Generate FAQ" style="width: 100%" />
      </form>
      <form action="admin.php" method="GET">
        <input type="hidden" name="generate_glossary" value="1" />
        <input type="submit" value="Generate Glossary" style="width: 100%" />
      </form>
      <hr />
      <form action="admin.php" method="GET">
        <input type="hidden" name="ping_rss" value="1" />
        <input type="submit" value="Ping RSS Aggregators" style="width: 100%" />
      </form>

    </td>
    <td valign=top align=left style="border: 0px solid green">
    <?php
      if(isset($_GET["generate_mainmenu"]))
        generate_side_menu();
      if(isset($_GET["generate_horzmenu"]))
        generate_horz_menu();
      if(isset($_GET["generate_sitemap"]))
        generate_sitemap();
      if(isset($_GET["generate_rss"]))
        generate_rss();
      if(isset($_GET["generate_googlesitemap"]))
        generate_googlesitemap();
      if(isset($_GET["generate_links"]))
        generate_links();
      if(isset($_GET["generate_glossary"]))
        generate_glossary();
      if(isset($_GET["generate_faq"]))
        generate_faq();
      if(isset($_GET["generate_tablature"]))
        generate_tablature();
      if(isset($_GET["generate_all"]))
      {
        generate_side_menu();
        generate_horz_menu();
        generate_sitemap();
        generate_rss();
        // generate_googlesitemap();
        generate_links();
        generate_faq();
        generate_glossary();
      }
    ?>
    </td>
  </tr>
</table>
</center>

<h1><font color=blue>SEO Features</font></h1>
<ul>
<li> Robots.txt abilitato </li>
<li> Link prev/next/top generati automaticamente (prev_next_map.php), per rafforzare le sezioni </li>
<li> Internal Linking: ancore con keywords: menus, glossario, faq, next/prev </li>
<li> SEF Directories Vere: rafforza sezioni e aiuta ad organizzare </li>
<li> Meta Tab Robots: index follow </li>
<li> Search Engine Friendly Urls </li>
<li> Uso dei tag h1 ovunque </li>
<li> Uso dei link titles ovunque </li>
<li> Presenza di un GLOSSARY  </li>
<li> Presenza di FAQ  </li>
<li> Presenza di una SITEMAP  </li>
<li> Uso di definition list in FAQ e GLOSSARY per aumentare la semantica </li>
<li> Titoli configurabili da content file </li>
<li> Keywords configurabili da content file </li>
<li> Utilizzo di link assoluti per aumentare la semantica del sito </li>
<li> Links direttamente nella homepage </li>
<li> Link titles </li>
</ul>

<h1><font color=blue>PHP Engine Features (oltre a quelle sopra)</font></h1>
<ul>
<li> Gestione template multipli specificati per pagina </li>
<li> Generazione automatica link page & quick links, testuali, immagini, link of the day </li>
<li> Generazione automatica sitemap page </li>
<li> Generazione automatica rssfeed </li>
<li> Generazione automatica glossario </li>
<li> Generazione automatica faq </li>
<li> Generazione mappa prev/next </li>
<li> Generazione menu piatto degli articoli </li>
<li> Generazione menu' superiore </li>
<li> Search engine friendly URLs + directories in URL </li>
<li> Content organizzabile in directories </li>
</ul>

<h1><font color=blue>TODO LIST</font></h1>

<li> Ricontrolla Formato RSS 2.0 </li>
<li> Ricontrolla se il tuo sito e' HTML o XHTML, vedi se servono header particolari </li>
<li> <font color=red> CONCENTRATI SUI VOLI NON SUI VIAGGI </font></li>
<li> <font color=red> COMINCIA A SCRIVERE E PROGETTARE I CONTENTUI; POI TI OCCUPI DELLO STILE</font></li>
<li> <font color=red> LISTA PRIMARY KEYS E SECONDARY KEYS E PIANIFICA IL CONTENUTO DEL SITO </font> </li>
<li> <font color=red> Fai lista delle keyword/jargon presenti negli adverts del sito, adesso che i contenuti sono "neutri"</font> </li>
<li> <font color=red> Usa keyword low-cost, tickets, airline. </font> </li>
<li> CHECK XHTML CODE CLEANESS DI TUTTE LE PAGINE</li>
<li> Controlla compatibilita' explorer</li>
<li> Comincia styling base e metti css class styling</li>
<li> Spell checking ignicosa</li>
<li> Mimetizza AdSense</li>
<hr>
<li> Implementa auto ping e registra a RSS/ATOM feeds aggregators. </li>
<li> Implementa ATOM ?</li>
<li> Crea pagina link to us con codice HTML e keywords, form per inviare il link ai siti dei visitatori </li>
<li> Sistema di Keyword clouds come sui blog prese dalle keyword degli header </li>
<li> Ma negli RSS ci puo' stare il codice HTML? e non e' encodato? togli encondig allora</li>
<li> Tool che ti dice se le keyword sono effettivamente usate</li>
<li> Usare automaticamente i tag [b] [i] [u] per le parole chiave!</li>
<li> Navigation paths con keywords: aiuta la keyword density & inbound linking </li>

<h1><font color=blue>Guide Lines</font></h1>

<li>Siamo sicuri che le definition list non mi ammazzano il SEO?</li>
<li>I nomi degli articoli devono essere brevi ed accattivanti in modo che i tipi ci clicchino</li>
<li>SEO: Include in OpenDirectories, Google, Yahoo, MSN, spamma links in FORUMS, BLOGS, NEWSGROUPS, MAILLISTS </li>
<li>SEO: Registra sito web su http://www.directoryarchives.com e http://www.isedb.com </li>
<hr>
<li> Linka solamente sitiweb semanticamente correlati </li>
<li> Linka la tua homepage dalle altre pagine usando ancore con keyword concentrate </li>
<li> Linka siti rispettati, open, governativi e in genere amati da google come OPENDIR, Wiki, etc. </li>
<li> Gli articoli dovrebbero avere un'introduzione che permette di inserire keywords rilevanti all'inizio della pagina </li>
<li> Usa liste a pallettoni e bold per facilitare la lettura </li>
<li> Title tag, keywords, description e titoli H1 devono essere il piu' naturali possibili. </li>
<li> Title e Description delle pagine devono essere unici </li>
<li> I titoli delle pagine devono essere descrittivi e con keywords </li>
<li> Navigation: graphic tabs+textlinks </li>
<li> Metti parole mispelled nel tag keywords </li>
<li> I titoli e la descrizione devono colpire il lettore! </li>
<li> Comparison content: molte persone cercano le differenze tra un prodotto e l'altro </li>
<li> SEO Filenames non dovrebbero essere composti da piu' di 5 parole </li>
<li> SEO Directory names non dovrebbero essere composti da piu' di 2/3 parole </li>
<li> Le parole chiave devono essere sparse per tutto il testo degli articoli e possibilmente essere lontane tra loro </li>
<li> Non essere generico: usa esplicitamente parole chiave specifiche e sinonimi in tutta la struttura del documento </li>
<li> Metti sempre del testo  pulito con [p] nelle pagine (con keywords), non ci devono mai essere solamente H1/H2 e aiuta a strutturare la pagina </li>
<li> I titoli in H1 devono essere leggermente diversi dal titolo della pagina </li>
<li> Usa un solo H1 e piu' H2 nella pagina per strutturare la pagina </li>
