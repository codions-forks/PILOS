@extends('layouts.app')

@section('content')
    <div class="wrapper">





        <div class="background">
            <div class="container pt-9 pb-8">
                <div class="row">
                    <div class="col-md-12 col-sm-12 text-center">
                        <img src="https://test.pilos-thm.de/b/logo/test/Pilos.svg" style="height: 70px;max-width: 100%;margin-bottom: 21px;">
                        <h1 id="main-text" class="display-4 mb-4">Willkommen zu PILOS.</h1>
                        <p class="lead offset-lg-2 col-lg-8 col-sm-12 ">
                            PILOS ist die Plattform für interaktive Live-Online-Seminare des Fachbereichs Gesundheit der THM.
                        </p>
                        <p class="lead offset-lg-2 col-lg-8 col-sm-12 ">
                            Die Grundlage dafür ist das auch in anderen Hochschulen zum Einsatz kommende Open Source Projekt <a href="https://bigbluebutton.org/" targent="_blank">BigBlueButton</a>.
                        </p>
                        <p class="lead offset-lg-2 col-lg-8 col-sm-12 ">
                            Sie können für Ihre Veranstaltungen, Seminare und Übungen Räume erstellen und über einen praktischen Kurzlink Studenten, Mitarbeiter und Gäste einladen.
                            Zusätzlich können Sie die Plattform auch für Prüfungen und Besprechnungen nutzen.
                        </p>
                    </div>




                </div>
            </div>
        </div>



        <div class="container mt-8 mb-6 text-center">

            <h2 class="mb-6">Was PILOS alles kann</h2>

            <div class="row feature-stamp">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-item">
                        <div class="stamp bg-primary"><i class="fas fa-comments fa-2x"></i></div>
                        <h4 class="mt-4">Chat</h4>
                        <p>Versende öffentliche und private Nachrichten</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <span class="stamp bg-primary"><i class="fas fa-video fa-2x"></i></span>
                    <h4 class="mt-4">Webcams</h4>
                    <p>Ermögliche persönliche visuelle Unterhaltungen</p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <span class="stamp bg-primary"><i class="fas fa-headset fa-2x"></i></span>
                    <h4 class="mt-4">Audio</h4>
                    <p>Kommuniziere über eine qualitativ hochwertige Audioverbindung</p>
                </div>
            </div>
            <div class="row feature-stamp">
                <div class="col-lg-4 col-md-6">
                    <span class="stamp bg-primary"><i class="fas fa-thumbs-up fa-2x"></i></span>
                    <h4 class="mt-4">Emojis</h4>
                    <p>Direktes Zuschauerfeedback</p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-item">
                        <div class="stamp bg-primary"><i class="fas fa-chalkboard-teacher fa-2x"></i></div>
                        <h4 class="mt-4">Kleingruppen</h4>
                        <p>Teile Nutzer für Gruppenarbeiten in Kleingruppen auf</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <span class="stamp bg-primary"><i class="fas fa-users fa-2x"></i></span>
                    <h4 class="mt-4">Umfragen</h4>
                    <p>Interagiere zu jeder Zeit mit den Zuschauern</p>
                </div>
            </div>
            <div class="row feature-stamp">
                <div class="col-lg-4 col-md-6">
                    <span class="stamp bg-primary"><i class="fas fa-desktop fa-2x"></i></span>
                    <h4 class="mt-4">Bildschirmfreigabe</h4>
                    <p>Teile den Inhalt deines Bildschirms oder Anwendung</p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <span class="stamp bg-primary"><i class="fas fa-chalkboard-teacher fa-2x"></i></span>
                    <h4 class="mt-4">Mehrnutzer Whiteboard</h4>
                    <p>Nutze eine gemeinsame interaktive virtuelle Tafel</p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <span class="stamp bg-primary"><i class="fas fa-film fa-2x"></i></span>
                    <h4 class="mt-4">Aufzeichnung</h4>
                    <p>Zeichne die Unterhaltung auf und stelle diese später zur Verfügung</p>
                </div>
            </div>
        </div>


    </div>
@endsection
