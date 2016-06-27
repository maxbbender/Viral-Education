<?php
/**
 *  Project Talos - Learning Language Platform
 *
 *  User's will be able to upload texts and read them. This is the homepage where you
 *  have to login to view statistics, and see texts that are assigned to you
 *
 * @Author: Max Bender
 * @Version: 1.0
 */

//Start Session
session_start();
?>
<html>
<head>
    <title> Viral Education - Home </title>
    <?php include_once 'includes/css_links.php'; ?>
    <style>
        #titleText {
            font-family: 'Questrial', sans-serif;
            text-align: center;
            position: relative;
            top: 10%
        }
        #main {
            font-family: 'Open Sans', sans-serif;
            font-size:400%;
        }
        .sub {
            font-family: 'Questrial', sans-serif;
            font-size: 200%;
        }
        .sub2 {
            font-family: 'Questrial', sans-serif;
            font-size: 300%;
        }
        .quest {
            font-family: 'Questrial', sans-serif;
        }
        #title {
            height:500px;
            background-image: url('/img/green_dust_scratch.png');
        }
        .helper {
            display: inline-block;
            height: 100%;
            vertical-align: middle;
        }

        img {
            vertical-align: middle;
            max-height: 128px;
        }
    </style>
    <link href='http://fonts.googleapis.com/css?family=Questrial' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Luckiest+Guy' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:600italic' rel='stylesheet' type='text/css'>
</head>
<body>
<!-- Navigation Bar -->
<?php include_once 'includes/main_nav.php'; ?>
<div id="title">
    <div id="titleText">
        <p id="main">Welcome to Viral Education</p><br><br>
        <p class="sub" id="sub">Learning by Reading; What you Want, When you Want</p><br><br>
        <a href="login.php" class="button radius large">Get Started</a><br><br>
        <p id="claimer">A Research and Development project of the Weiss Language Center at Marist College</p>
    </div>
</div>
<div class="row" id="wrapper">
    <!--<h1 class="text-center">Welcome to Viral Education</h1>
    <h4 class="text-center subheader">Learning by Reading; What you Want, When you Want</h4>-->
    <div class="row">

    </div>
    <hr>
    <!-- Information Panels -->
    <div class="row" data-equalizer>
        <div class="row">
            <div class="small-6 columns" data-equalizer-watch>
                <div class="row">
                    <div class="small-4 columns text-center" style="max-height:128px">
                        <span class="helper"></span><img class="" src="/img/read.png">
                    </div>
                    <div class="small-8 columns">
                        <h2 class="quest">Read</h2>
                        <h4 class="subheader quest">Leaders and Learners can upload or browse through our vast library of
                            texts that other users have submitted.</h4>
                    </div>
                </div>
            </div>
            <div class="small-6 columns" data-equalizer-watch>

                <div class="row">
                    <div class="small-4 columns text-center" style="max-height:128px">
                        <span class="helper"></span><img class="" src="/img/information.png">
                    </div>
                    <div class="small-8 columns">
                        <h2 class="quest">Comprehend</h2>
                        <h4 class="subheader quest">Use our reading support technology to help you comprehend foreign languages like never before.</h4>
                    </div>
                </div>
            </div>
        </div>
    </div><br><br>
    <div class="row">
        <div class="small-2 columns text-center">
            <img src="img/arrow_down.png">
        </div>
        <div class="small-8 columns text-center">
            <p class="sub2 text-center">Scroll Down to Read More</p>
        </div>
        <div class="small-2 columns text-center">
            <img src="img/arrow_down.png">
        </div>
    </div>
    <br><br><br><br><br><br>
    <div class="row">
        <div class="small-12 columns small-centered">
            <h2 class="">Learn by Reading:</h2>
            <!--<h5 class="subheader">Who <strong>really</strong> wants to read a 250 page text about some guy who lived in
                Costa Rica over 200 years ago that once wrote a memoir about a dandelion that broke from its stem and
                floated off into the horizon only to never never be seen again? Even the description is hard to read
                without skimming some words.<br><br>

                Here at <strong>Viral Education</strong> we want you to read about topics you are interested while
                learning a new language at the same time. If you are passionate about Medicine, Game of Thrones, <span
                    data-tooltip class="has-tip"
                    title="Hint: Something I experience everytime I walk into the bathroom...">Eisoptrophobia</span> or
                even possibly a guy who lived in Costa Rica 200 years ago; this is the place to read about something you
                love while at the same time reinforcing the fundamentals of a new language.</h5>-->
            <p>Viral Education is an engine to create comprehensible input in the target language by creating optimal motivation through autonomous learning and multimedia glossing so that learners achieve incidental vocabulary acquisition in a constant i+1 environment. The idea is that if learners can read read what they like, when they like and that every word becomes linked to a variety of references so that learners comprehend what they are reading, remember words in a context and explore knowledge surrounding a text.
            <p>
        </div>
    </div>
    <br>


    <div class="row" data-equalizer>
        <div class="row">
            <div class="small-12 columns small-centered">
                <ul class="tabs" data-tab role="tablist" style="width:1200px;">
                    <li class="tab-title" role="presentational" style="width:25%;">
                        <a href="#panel1" role="tab" tabindex="0" aria-selected="false" controls="panel1">
                            <h4 class="text-center">Use in Classes</h4>

                            <div class="row">
                                <div class="small-10 columns small-centered text-center">
                                    <img class="" src="/img/classroom128.png">
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="tab-title" role="presentational" style="width:25%;">
                        <a href="#panel2" role="tab" tabindex="0" aria-selected="false" controls="panel2">
                            <h4 class="text-center">Read Independently</h4>

                            <div class="row">
                                <div class="small-10 text-center columns small-centered">
                                    <img class="" src="/img/student128.png">
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="tab-title" role="presentational" style="width:25%;">
                        <a href="#panel3" role="tab" tabindex="0" aria-selected="false" controls="panel3">
                            <h4 class="text-center">Track Statistics</h4>

                            <div class="row">
                                <div class="small-10 text-center columns small-centered">
                                    <img class="" src="/img/statistics128.png">
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="tab-title" role="presentational" style="width:25%;">
                        <a href="#panel4" role="tab" tabindex="0" aria-selected="false" controls="panel4">
                            <h4 class="text-center">Learn Vocabulary</h4>

                            <div class="row">
                                <div class="small-10 text-center columns small-centered">
                                    <img class="" src="/img/translation128.png">
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row tabs-content">
        <section role="tabpanel" aria-hidden="true" class="content" id="panel1">
            <h2>Use in Classes</h2>
            <p>Using Viral Education you can register as a Leader and create texts for your students to read using our reading support technology. Any kind of texts can be used! You can then track statistics for your classes and see what words your students are defining most often or view per student stats. See "Track Statistics" for more information</p>
        </section>
        <section role="tabpanel" aria-hidden="true" class="content" id="panel2">
            <h2>Read Independently</h2>
            <p>Even if you are trying to learn a language by yourself you can upload texts and read them with the assitance of our reading support technology. Once you have registered just click the "Create Text" button under the learner tab and go ahead and copy and paste a text. Then all you have to do is view it in "My Readings" and go ahead and learn!</p>
        </section>
        <section role="tabpanel" aria-hidden="true" class="content" id="panel3">
            <h2>Track Statistics</h2>
            <p>When you assign texts to a class they are then set up to track statistics. Statistics can be viewed in a variety of ways; by each learner, by class or by text.</p>
        </section>
        <section role="tabpanel" aria-hidden="true" class="content" id="panel4">
            <h2>Learn Vocabulary</h2>
            <p>Using our reading support technology learn new vocabulary in the texts that you are reading. If you don't know a word all you have to do is click on it and it will be defined and translated right on the page! How cool is that!</p>
        </section>
    </div>

</div>
<?php include_once 'includes/javascript_basic.php'; ?>
</body>
</html>
			