<?php

/*
@author Kevin Haltiner <kevin.haltiner@bluewin.ch>
@copyright 2024 Kevin Haltiner
@license OpenGL
@version 1.0.0

*/
//Quellen: PHP Manuel, Stackoverflow


system("clear");

// Test Gültige Note
function GradeCheck($note)
{
    return preg_match('/^[1-6](\.\d{1,2})?$/', $note) === 1;
}

// Funktion zum Berechnen des Notenschnitts
function calculateAverage($noten)
{
    if (count($noten) === 0) {
        return 0;
    }
    return array_sum($noten) / count($noten);
}

// Funktion, um Noten für ein Fach hinzuzufügen oder anzuzeigen
function start()
{
    $file = "Noten.txt";

    // Wenn die Datei nicht existiert, wird eine neue Datei erstellt
    if (!file_exists($file)) {
        file_put_contents($file, "Mathematik: \nEnglisch: \nABU: \nSport: \n");
    }

    do {
        // Menü anzeigen
        echo "\nWas möchten Sie tun?\n";
        echo "1. Noten für ein Fach hinzufügen\n";
        echo "2. Notenschnitt aller Fächer anzeigen\n";
        echo "3. Noten für ein Fach anzeigen\n";
        echo "x. Programm beenden\n";
        $choice = strtolower(readline("Ihre Auswahl (1, 2, 3 oder x): "));

        switch ($choice) {
            case '1':
                addGrade($file);  // Noten hinzufügen
                readline("Drücken Sie Enter, um ins Menü zurückzukehren...");
                system("clear");
                break;
            case '2':
                showAllAverages($file);  // Notenschnitt aller Fächer anzeigen
                readline("Drücken Sie Enter, um ins Menü zurückzukehren...");
                system("clear");
                break;
            case '3':
                showSubjectGrades($file);  // Noten für ein Fach anzeigen
                readline("Drücken Sie Enter, um ins Menü zurückzukehren...");
                system("clear");
                break;
            case 'x':
                echo "Programm wird beendet. Auf Wiedersehen!\n"; //Beendet das Programm
                break;
            default:
                system("clear");
                echo "Ungültige Auswahl! Bitte wählen Sie 1, 2, 3 oder x.\n";
                sleep(1);
                break;
        }
    } while ($choice !== 'x');
}

// Funktion zum Hinzufügen einer Note zu einem Fach
function addGrade($file)
{
    system("clear");

    // Fächerauswahl
    $subjects = ["Mathematik", "Englisch", "ABU", "Sport", "Modul 117", "Modul 231"];
    echo "\nWelches Fach möchten Sie auswählen?\n";
    foreach ($subjects as $index => $subject) {
        echo ($index + 1) . ". $subject\n";
    }

    //Auswahl
    $subjectChoice = (int) readline("Ihre Auswahl (1 bis 6): ");
    if ($subjectChoice < 1 || $subjectChoice > 6) {
        echo "Ungültige Auswahl. Bitte versuchen Sie es erneut.\n";
        return;
    }

    //Noten eintragen
    $selectedSubject = $subjects[$subjectChoice - 1];
    echo "\nSie haben $selectedSubject gewählt.\n";
    $newGrade = readline("Geben Sie die Note ein (zwischen 1 und 6): ");

    //Überprüft ob Note korrekt ist
    if (GradeCheck($newGrade)) {
        // Daten aus der Datei laden
        $data = file($file, FILE_IGNORE_NEW_LINES);
        foreach ($data as &$line) {
            if (strpos($line, $selectedSubject) === 0) {
                $line .= " $newGrade";
                break;
            }
        }

        //Bestätigung
        file_put_contents($file, implode(PHP_EOL, $data) . PHP_EOL);
        echo "Note erfolgreich hinzugefügt!\n";
    } else {
        echo "Ungültige Note. Bitte geben Sie eine Zahl zwischen 1 und 6 ein.\n";
    }
}

// Funktion zum Anzeigen des Notenschnitts aller Fächer
function showAllAverages($file)
{
    system("clear");

    $data = file($file, FILE_IGNORE_NEW_LINES);

    echo "\nNotenschnitte für alle Fächer:\n";
    foreach ($data as $line) {
        [$subject, $grades] = explode(":", $line);
        $gradesArray = array_filter(explode(" ", trim($grades)));

        if (count($gradesArray) > 0) {
            $average = calculateAverage($gradesArray);
            echo "$subject: Durchschnitt = " . number_format($average, 2) . "\n";
        } else {
            echo "$subject: Keine Noten vorhanden.\n";
        }
    }
}

// Funktion zum Anzeigen der Noten für bestimmtes Fach
function showSubjectGrades($file)
{
    system("clear");

    // Fächer zur Auswahl
    $subjects = ["Mathematik", "Englisch", "ABU", "Sport, Modul 117", "Modul 231"];
    echo "\nWelches Fach möchten Sie anzeigen?\n";
    foreach ($subjects as $index => $subject) {
        echo ($index + 1) . ". $subject\n";
    }

    $subjectChoice = (int) readline("Ihre Auswahl (1 bis 6): ");
    if ($subjectChoice < 1 || $subjectChoice > 6) {
        echo "Ungültige Auswahl. Bitte versuchen Sie es erneut.\n";
        return;
    }

    $selectedSubject = $subjects[$subjectChoice - 1];
    echo "Sie haben $selectedSubject gewählt.\n";

    // Daten aus txt laden
    $data = file($file, FILE_IGNORE_NEW_LINES);
    foreach ($data as $line) {
        if (strpos($line, $selectedSubject) === 0) {
            [$subject, $grades] = explode(":", $line);
            $gradesArray = array_filter(explode(" ", trim($grades)));
            echo "$subject: " . (count($gradesArray) > 0 ? implode(" | ", $gradesArray) : "Keine Noten vorhanden") . "\n";
            break;
        }
    }
}

// Start
start();
