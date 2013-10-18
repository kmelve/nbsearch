<?php ini_set('display_errors', 'On'); ?>
<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->

<head>
<meta charset="utf-8" />
  <meta name="viewport" content="width=device-width">
  
	<meta property="og:title" content="NB PDF S&oslash;k" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://jekyll-hyde.no/nbsearch" />
	<meta property="og:image" content="http://jekyll-hyde.no/nbsearch/img/logo.png" />
	<meta property="og:description" content="NB PDF SØK (ß) er et verktøy for &aring; lage en enkel liste med nedlastningslenker til digitalisert innhold." />

  <title>NB PDF Søk</title>

  
  <link rel="stylesheet" href="css/foundation.css">
  <style>
  	body {
	  	font-family: "Myriad Pro", "Helvetica Neue", Helvetica, sans-serif;
  	}
  	.red {
	  	color: #bf0a30;
  	}
  	pre {
	  white-space: pre-line;
	}
  </style>

  <script src="js/vendor/custom.modernizr.js"></script>

</head>
<?php
if (isset($_GET["searchword"])) {
	$searchword = $_GET["searchword"];
	$itemsPerPage = $_GET["itemsPerPage"];
//	$filter = "mediatype:B&oslash;ker";
	$filter = "mediatype:".$_GET["filter"];
//	$sort = "sort=".$_GET["sort"];
	$freetext = $_GET["freetext"];

//	$tilgjengeligeaviser = array("Firda Folkeblad","Folkebladet for Sogn og Fjordane","Fylkestidende for Sogn og Fjordane","Hardanger","Nordlands Avis","Nordre Bergenhus Amtstidende","Nordre Bergenhus Folkeblad","Ranens Tidende","Søndfjords Avis");


if(function_exists("curl_init")){ // Check if cURL is available
	
	$str = "?q=".urlencode($searchword)."&ft=".$freetext."&itemsPerPage=".$itemsPerPage."&filter=".urlencode($filter)/*."&sort=".$sort*/; // Sort-paramater disabled
	
	
	// In case I need the URLs other places, it's nice to have them as variables
	$searchbaseurl = "http://www.nb.no/services/search/v2/search";
	$nburnbaseurl = "http://urn.nb.no/";
	$downloadbaseurl = "http://www.nb.no/nbsok/content/pdf?urn="; 
	$url = $searchbaseurl.$str; 

	
	$curl = curl_init(); // I find the setopt_array a bit more organized than the inline init
	
	curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER 		=> true,
			CURLOPT_URL 				=> $url,
//			CURLOPT_PROGRESSFUNCTION	=>	"progress", // This is to be used for the progressbar
			CURLOPT_HTTPHEADER			=> array('Content-Type: text/xml; charset="UTF-8"'),
			CURLOPT_ENCODING			=> "UTF-8",
			CURLOPT_USERAGENT 			=> "NB PDF SØK",
			CURLOPT_NOPROGRESS 			=> false
		));

		$data = curl_exec($curl); //takes the data from cURL and puts in it the $data variable
		$header  = curl_getinfo($curl); //headerinfo for debugging and so on



		curl_close($curl); // closes the cURL session [is that the right term, "session"?]
		
	} // end if curl_intit
	
	} //end if isset $_GET

?>
<body>
<div class="row">
	<div class="large-12 columns">
		<h1><a href="http://jekyll-hyde.no/nbsearch"><span class="red">NB</span> PDF SØK (ß)</a></h1>
	</div>
</div>
				
<div class="row">
	<div class="large-6 columns">
		<p><span class="red">NB</span> PDF SØK (ß) er et verktøy for å lage en enkel liste med nedlastningslenker til digitaliserte aviser.</p>
		<hr />
		<p>Obs! Kan ta litt tid å laste inn ved høyt antall søketreff. Nyere utgivelser kan vise seg å være <a href="https://twitter.com/bibvenn/status/369371405688664064">utilgjengelige</a>.</p>
		
	</div>
	<div class="large-6 columns">
		<form class="custom" action="index.php" method="get" accept-charset="utf-8" data-abide>
			<fieldset>
				<legend>Søk etter pdf-filer i nb.no</legend>
				<div class="row">
					<div class="large-9 columns">
						<label>Søkeord</label>
						<input type="search" name="searchword" <?php if(isset($searchword)) { echo 'value="'.$searchword.'"'; } ?> placeholder="Bruk * for wildcard" required>
					</div>
					<div class="large-3 columns">
					<label>Fritekst</label>
						<div class="switch round">
						 
 						  <input id="x1" name="freetext" value="false" type="radio" <?php if($_GET["freetext"] == "false")  { echo "checked";}?>>
						  <label for="x1" onclick="">Av</label>

						 
						  <input id="x" name="freetext" value="true" type="radio" <?php if(($_GET["freetext"] == "true") || (empty($searchword) == true)) { echo "checked";}?>>
						  <label for="x" onclick="">På</label>
						
						
						
						
						  <span></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="large-4 columns">
					<label>Maks antall treff</label>
						<input type="number" value="<?php if(isset($itemsPerPage)) { echo $itemsPerPage; } else { echo "10"; } ?>" name="itemsPerPage" placeholder="<?php if(isset($itemsPerPage)) { echo $itemsPerPage; } else { echo "10"; } ?>" min="1" max="9999">
					</div>
					<div class="large-8 columns">
						<label for="customDropdown1">Medietype</label>
						<select name="filter" id="customDropdown1" class="medium">
								<option value="" DISABLED>Velg medietype</option>
								<?php 
									 $i=0;
									 $array=array("Aviser", "Bøker", "Artikler", "Film", "Radio");
									while ($i<5){ ?>
									<option value="<?php echo $array[$i];?>" <?php if ((empty($searchword) == false) && ($_GET["filter"] == $array[$i])) { echo 'selected';} ?>><?php echo $array[$i];?></option>
									<?php $i++; } ?>
							
						</select>
					</div>
				</div>
				<button class="button prefix" type="submit">Søk</button>
			</fieldset>
	</form>
	</div>
	<div class="large-6 columns">
	</div>
</div>

<div class="row">
	<div class="large-12 columns">
		<div class="section-container auto" data-section>
			<section>
				<p class="title" data-section-title><a href="#panel1">Søketreff</a></p>
				<div class="content" data-section-content>
					<div class="empty child"></div>
					<span class="label">Klikk på lenkene for å laste ned.</span>
<!-- 					<button class="nedlastningsknapp">Klikk for å laste ned alle filene</button><span label="alert label">Bruk med omhu!</span> -->
	
						<table>
							<thead
								<tr>
									<th width="33%">Kilde</th>
									<th>Utdrag</th>
									<th width="13%">Åpne i <a href="http://nb.no">nb.no</a></th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(isset($data)) $doc = new SimpleXmlElement($data); // No need to run of $data isn't set for some reason
								if(isset($doc->entry)) parseAtom($doc); //No need to run if the XML-array doesn't have the proper entries
				
								function parseAtom($xml){
									$tilgjengelig = array();
									foreach($xml->entry as $entry) {
										$kilde = $entry->title;
										//nb namespaces
										$namespaces = $entry->getNameSpaces(true);
										$nb = $entry->children($namespaces['nb']);
										$utdrag = $nb->snippet;
										global $downloadbaseurl;
										global $nburnbaseurl;
										global $tilgjengeligeaviser;
										$pdfurl = $downloadbaseurl . $nb->urn;
										$nblink = $nburnbaseurl . $nb->urn;
										$tilgjengelig[] = $nb->digital;
										$digital = $nb->digital;
//										$tilgjengelighet = preg_match($kilde, $tilgjengeligeaviser);
										
										if ($digital == 'false') {
												continue;
										}
									
										echo '<tr><td><a class="nedlastningslenker" href="'.$pdfurl.'">'.$kilde.'</a>';
										
										echo '</td><td>'.$utdrag.'</td>';
										echo '<td style="text-align:center"><a target="_blank" href="'.$nblink.'"><img src="img/box-expand.png" alt="NB →" border="0" /></a></td></tr>';
									} // end foreach
									$telling = count(array_filter($tilgjengelig, function ($n) { return $n == 'false'; })); // Counts all instances of digital:false in loop
									
									if ($telling == count($xml->entry)) { //compare the numbers of all digital:false with whole array, if equal output alert
									echo '<span class="alert label">Ingen tilgjengelige treff</a>';
									}
								} //end function
								?>
							

							</tbody>
						</table>
				</div>
			</section>
			
			<section>
				<p class="title" data-section-title><a href="#panel2">Lenkeliste</a></p>
				<div class="content" data-section-content>
					<div class="empty child"></div>
					<span class="label">Lenkeliste (kan kopieres inn i en tekstfil som kan kjøres i <span data-tooltip class="has-tip" title="$ wget -i lenkeliste.txt">wget</span>)</span>
					<pre id="linkliste">
					<?php
						if(isset($doc->entry)) makelist($doc); //No need to run if the XML-array doesn't have the proper entries

						function makelist($xml) {
							foreach($xml->entry as $entry){
							$kilde = $entry->title;
							//nb namespaces
							$namespaces = $entry->getNameSpaces(true);
							$nb = $entry->children($namespaces['nb']);
							global $downloadbaseurl;
							$pdfurl = $downloadbaseurl . $nb->urn;
							
							if($pdfurl !== $downloadbaseurl) {
								echo $pdfurl."<br />";								
								} //endif pdfurl

							} //end foreach
						} //end function 
					?>
					</pre>
				</div>
			</section>

			<section>
				<p class="title" data-section-title><a href="#panel3">Header-info fra cURL</a></p>
				<div class="content" data-section-content>
					<pre>
					<?php print_r($header); ?>
					</pre>
				</div>
			</section>
			<section>
				<p class="title" data-section-title><a href="#panel4">simpleXmlElement Array</a></p>
				<div class="content" data-section-content>
					<pre>
					<?php print_r($doc); ?>
					</pre>
				</div>
			</section>
			<section>
				<p class="title" data-section-title><a href="#panel5">Changelog</a></p>
				<div class="content" data-section-content>
					<p>
					<ul class="no-bullet">
						<li>19/08/2013
							<ul>
								<li>Nå med GET i stedet for PHP. Gjør søkene delbare</li>
								<li>Skjemaet "husker"</li>
								<li>Markerer utilgjengelige treff.</li>
								<li>Kan nå velge mellom bøker og aviser</li>
								<li>Bedre støtte for ikke-typiske tegn (æ ø å)</li>
								<li>Lagt til lenker til oppføringene i nettsidene til Nasjonalbiblioteket</li>
								<li>Nettsiden vises som den skal uten cURL-data</li>
							</ul>
						</li>
						<li>16/08/2013
							<ul>
								<li>Grunnfunksjonalitet på plass.</li>
							</ul>
						</li>
					</ul>
					</ul>
					</p>
				</div>
			</section>

			<section>
				<p class="title" data-section-title><a href="#panel6">To-Do</a></p>
				<div class="content" data-section-content>
					<p>
					<ul class="no-bullet">
						<li>Fikse PHPen slik at siden laster skikkelig uten søkedata</li>
						<li>Flere søkevalg, inkludert dato-avgrensning og sortering</li>
						<li>Legge inn progressbar</li>
						<li>Filtrere ut utilgjengelige søketreff</li>
						<li>Mulighet for å eksportere en enkel .txt med alle lenkene oppført i en liste (til i wget o.l.)</li>
						<li>Gjøre tabell interaktiv</li>
						<li>Legge inn i en github-rep</li>
					</ul>
					</p>
				</div>
			</section>

			
		</div>
	</div>
	</div>
</div>
<footer>
	<div class="panel">
		<div class="row">
			<div class="large-3 columns">
				<ul class="no-bullet"><h5>Laget av</h5>
					<li><a href="mailto:knut.melvaer@gmail.com">Knut Melvær</a> / <a href="http://twitter.com/kmelve">@kmelve</a></li>
					<li><?php echo date("Y"); ?></li>
				</ul>
			</div>
			<div class="large-4 columns">
				<ul class="no-bullet"><h5>Ressurser</h5>
					<li><a href="http://foundation.zurb.com">ZURB Foundation</a></li>
					<li><a href="http://www.nb.no/services/search/v2/">Nasjonalbibliotekets API for søk</a></li>
					<li><a href="http://php.net/manual/en/book.curl.php">php cURL</a></li>
					<li><a href="http://php.net/manual/en/class.simplexmlelement.php">SimpleXMLElement</a>
				</ul>
			</div>
			<div class="large-5 columns">
				<ul class="no-bullet"><h5>Sjekk også ut</h5>
					<li><a href="http://www.bibliotekarensbestevenn.no">Bibliotekarensbestevenn.no</a> av <a href="http://www.sundaune.no">Håkon M.E. Sundaune</a></li>
				</ul>
			</div>
		</div>
	</div>
</footer>

 <script src="http://code.jquery.com/jquery-latest.js"></script>  
  <script src="js/foundation.min.js"></script>
  
  
  <script src="js/foundation/foundation.js"></script>
  
  <script src="js/foundation/foundation.interchange.js"></script>
  
  <script src="js/foundation/foundation.abide.js"></script>
  
  <script src="js/foundation/foundation.dropdown.js"></script>
  
  <script src="js/foundation/foundation.placeholder.js"></script>
  
  <script src="js/foundation/foundation.forms.js"></script>
  
  <script src="js/foundation/foundation.alerts.js"></script>
  
  <script src="js/foundation/foundation.magellan.js"></script>
  
  <script src="js/foundation/foundation.reveal.js"></script>
  
  <script src="js/foundation/foundation.tooltips.js"></script>
  
  <script src="js/foundation/foundation.clearing.js"></script>
  
  <script src="js/foundation/foundation.cookie.js"></script>
  
  <script src="js/foundation/foundation.joyride.js"></script>
  
<!--   <script src="js/foundation/foundation.orbit.js"></script> -->
  
  <script src="js/foundation/foundation.section.js"></script>
  
  <script src="js/foundation/foundation.topbar.js"></script>
  
  <script src="js/vendor/jquery.multiDownload.js"></script>
  
<!--
  <script src="js/vendor/FileSaver.js"></script>
  <script src="js/vendor/Blob.js"></script>
-->
  
  <script>
	
	$(function() {
	   $(document).foundation();
	$('.nedlastningslenker').multiDownloadAdd();
	$('.nedlastningsknapp').multiDownload('click', { delay: 500 });
	  
	});    
</script>
</body>
</html>
