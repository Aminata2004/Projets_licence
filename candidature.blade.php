{{--@extends('layouts.app')--}}

{{--@section('titre')--}}
{{--    Candidats--}}
{{--@endsection--}}

{{--@section('content')--}}
{{--    <div class="content">--}}
{{--        <div id="candidats-container">--}}
{{--            @include('layouts.partials.candidats', ['candidatures' => $candidatures])--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

{{--@section('scripts')--}}
{{--    <script>--}}
{{--        $(document).ready(function() {--}}
{{--            $(document).on('click', '#candidats-container .pagination a', function(e) {--}}
{{--                e.preventDefault();--}}
{{--                let url = $(this).attr('href');--}}
{{--                fetchCandidats(url);--}}
{{--            });--}}

{{--            function fetchCandidats(url) {--}}
{{--                $.ajax({--}}
{{--                    url: url,--}}
{{--                    type: 'GET',--}}
{{--                    dataType: 'html',--}}
{{--                    success: function(data) {--}}
{{--                        $('#candidats-container').html(data);--}}
{{--                    },--}}
{{--                    error: function() {--}}
{{--                        alert('Erreur lors du chargement des candidats.');--}}
{{--                    }--}}
{{--                });--}}
{{--            }--}}
{{--        });--}}
{{--    </script>--}}
{{--@endsection--}}
@extends('layouts.app')
@section('titre', 'Dépouillement des candidats')

@section('content')
    <div class="content">
        <h2 class="mb-4">Candidatures pour : {{ $offre->titre }}</h2>

        <div id="candidats-container">
            @include('layouts.partials.candidats', ['candidatures' => $candidatures])
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){

            function updateCandidatureStatus(candidatureId, status) {
                let note = null;
                let remarque = null;

                if(status === 'accepte') {
                    // Demande note
                    note = prompt("Entrez une note (1 à 5) :");
                    if(!note) return;

                    note = parseInt(note);
                    if(isNaN(note) || note < 1 || note > 5) {
                        alert("Veuillez entrer un nombre valide entre 1 et 5.");
                        return;
                    }

                    // Demande remarque
                    remarque = prompt("Entrez un commentaire (optionnel) :") || null;
                }

                $.ajax({
                    url: '{{ route("candidature.updateStatus", ":id") }}'.replace(':id', candidatureId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status,
                        note: note,
                        remarque: remarque
                    },
                    success: function(response){
                        if(response.success){
                            Swal.fire({
                                icon: 'success',
                                title: 'Mise à jour réussie !',
                                text: 'La candidature a été évaluée avec succès.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Erreur lors de la mise à jour.'
                            });
                        }
                    },
                    error: function(xhr){
                        alert('Une erreur est survenue : ' + xhr.responseText);
                    }
                });
            }

            function envoyerAlerte(candidatureId, type) {
                let url = '{{ route("candidature.alerte", ["id" => ":id"]) }}'; // Laravel garde :id
                url = url.replace(':id', candidatureId); // JS remplace ici

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        type: type,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Succès', response.message, 'success');
                    },
                    error: function() {
                        Swal.fire('Erreur', 'Impossible d’envoyer l’alerte.', 'error');
                    }
                });
            }

            // Boutons approuver / refuser
            $(document).on('click', '.btn-approuver', function(){
                const id = $(this).data('id');
                updateCandidatureStatus(id, 'accepte');
            });

            $(document).on('click', '.btn-refuser', function(){
                const id = $(this).data('id');
                if(confirm('Êtes-vous sûr de vouloir refuser ce candidat ?')){
                    updateCandidatureStatus(id, 'refuse');
                }
            });

            // Bouton alerte entretien
            $(document).on('click', '.btn-alerte-entretien', function(){
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Alerter pour entretien ?',
                    text: "Un email sera envoyé au candidat pour l’inviter à un entretien.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, envoyer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        envoyerAlerte(id, 'entretien');
                    }
                });
            });

            // Bouton alerte définitive
            $(document).on('click', '.btn-alerte-definitif', function(){
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Confirmer la sélection définitive ?',
                    text: "Un email sera envoyé pour annoncer la sélection définitive.",
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, envoyer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        envoyerAlerte(id, 'definitif');
                    }
                });
            });

        });
    </script>
@endsection
