<html>
<head>
    <title>
        Solr Query Results Analysis
    </title>
    <script type="text/javascript">
        function validateForm()
        {
            var query_btn = document.getElementById("query");
            var alertString = "Query field is empty, please provide valid inputs";
            if(query_btn.value.trim() == "")
            {
            alert(alertString);
            query_btn.focus();
            query_btn.select();
            return;
            }
        }
        function resetForm(form)
        {
            window.location="solr_web_test.php";
        }
    </script>
</head>
<body>
    <h2 style="text-align:center; margin-top:-40px;">Solr Query Search Result</h2>
    <form id="solrForm" name="form" method="GET" accept-charset="utf-8" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="q">Search:</label>
        <input id="query" name="q" type="text" size="30" placeholder="Enter a Query"/>
        <input type="radio" id="solr" name="ranking" value="solr" checked="TRUE" />Solr
        <input type="radio" id="PageRank" name="ranking" value="PageRank"/>PageRank
        <br><br>
        <input id="center" type="submit" onclick="validateForm();"/>
        <input type="reset" onclick="resetForm();"/>
    </form>
</body>
<?php
//echo "Page Loaded";
        $query="";
        $include = '/home/aditya/gitRepos/solr-php-client/Apache/Solr/Service.php';
        $domain = 'localhost';
        $port = 8983;
        $core = '/solr/myexample';
        $limit = 10;
        $results = false;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $query = isset($_GET["q"])?test_input($_GET["q"]) : false ;
  $rank = isset($_GET["ranking"])?test_input($_GET["ranking"]) : false ;
  //echo "Query Sent is ".$query . " rank selected is ". $rank . " " . $include;
  if($query)
  { 
    processQuery($query,$include,$port,$core,$limit);
  }
}

function processQuery($query,$include,$domain,$port,$core,$limit)
{
    //echo $query . " is received in processQuery " . $include . " " . $domain . " " . $port . " " . $core;
    require_once($include);
    $results="";
    $solr = new Apache_Solr_Service($domain, $port, $core);
    echo $solr;
    if (get_magic_quotes_gpc() == 1)
    {
        $query = stripslashes($query);

    }
    try
    {
        if($ranking == "solr")
        {
            $results = $solr->search($query, 0, $limit);
            echo $results;
        }
        else if($ranking == "PageRank")
            $results = $solr->search($query, 0, $limit, $additionalParameters);
    }
    
    catch (Exception $e)
    {
        die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
    }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
</html>
