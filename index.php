<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Курс валют бесплатно без смс и регистрации</title>
		<link rel="icon" href=icon.ico>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="project.css">
		<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
    </head>
	
<body>
    
    <table>
    <tr>
        <th id="app">
            <p @click=runPHP v-html=message></p>
            <p v-if=active v-for="line in messagePHP.split('\n')">{{line}}</p>
            <p v-else-if="source === 'file'" v-for="line in messageSource.split('\n')" v-html=line>{{line}}</p>
        </th>
        <th id="app2"> 
            <p @click=runPHP v-html=message></p>
            <p v-if=active v-for="line in messagePHP.split('\n')" v-html=line>{{line}}</p>
            <p v-else-if="source === 'api'" v-html=messageSource></p>
        </th> 
        <th id="app3">
            <p @click=runPHP v-html=message></p>
            <p v-if=active v-html=messagePHP></p>
            <p v-else-if="source === 'random'" v-html=messageSource></p>
        </th>
    </tr>
    </table>

<script>

"use strict"

    var app = new Vue({
	    el: "#app",
        data: {
            active: false,
            message: "Курс валют из файла",
            messagePHP: "",
            source: "<?php echo $_GET['source']; ?>",
            messageSource: `<?php showExchangeRate(file) ?>`
        },
        methods: {
            runPHP: function(){
                this.messagePHP = `<?php showExchangeRate(file) ?>`
                this.active = true
            }
        }
    })

    var app2 = new Vue({
	    el: "#app2",
        data: {
            active: false,
            message: "Курс валют API",
            messagePHP: "",
            source: "<?php echo $_GET['source']; ?>",
            messageSource: `<?php showExchangeRate(api) ?>`
        },
        methods: {
            runPHP: function(){
                this.messagePHP = `<?php showExchangeRate(api) ?>`
                this.active = true
            }
        }
    })

    var app3 = new Vue({
	    el: "#app3",
        data: {
            active: false,
            message: "Курс валют randomly",
            messagePHP: "",
            source: "<?php echo $_GET['source']; ?>",
            messageSource: "<?php showExchangeRate(random) ?>"
        
        },
        methods: {
            runPHP: function(){
                this.messagePHP = "<?php showExchangeRate(random) ?>"
                this.active = true
            }
        }
    })

</script>


<?php

    function _isCurl() { 
        return function_exists('curl_version'); 
    }

    function showExchangeRate($source) {
            switch ($source) {
        case "file":
            $myfile = fopen("exchange_rates.txt", "r") or die("Unable to open file!");
            echo fread($myfile,filesize("exchange_rates.txt"));
            fclose($myfile);
            break;
        case "api":
            get_course();
            break;
        case "random":
            $randomUSD = rand(2600, 2800) / 100;
            $randomEUR = rand(3000, 3200) / 100;
            echo "USD/UAH " . $randomUSD . "<br>EUR/UAH " . $randomEUR;
            break;
        default:
            echo "Something went wrong :(";
        }
    }


    function get_course() {
        if (_iscurl()) { 
            $url = "https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5"; 
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            global $output;
            $output = curl_exec($ch); 
            curl_close($ch); 
            if(!$output) return false;
            $courses = json_decode($output,true);
            $course_curr = false;
        } else { 
            echo "CURL is disabled"; 
        } 
    
        $curr = ['USD','EUR'];
        foreach ($courses as $course) {
            if (in_array($course['ccy'], $curr)) {
                $course_curr[$course['ccy']] = $course['buy'];
                $result = $course['ccy'] . "/UAH " . $course_curr[$course['ccy']] . "<br>";
                echo $course['ccy'] . "/UAH " . $course_curr[$course['ccy']] . "<br>";
            }
        }
    }    

?>

</body>
</html>
