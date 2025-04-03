@extends('admin.layouts.template')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <h4 class="fw-bold py-3 mb-4 text-center">Listes des clients</h4>

      <div class="card">
        <h5 class="card-header">Clients</h5>
        <div class="table-responsive text-nowrap">
          <table class="table table-dark">
            <thead>
              <tr style="text-align: center;">
                <th>Nom </th>
                <th>Prénoms</th>
                <th>Email</th>
                <th>Date heure d'inscription</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse ($users as $user)
              <tr style="text-align: center;">
               
                <td>{{ $user->name }}</td>
                <td>{{ $user->prenom }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at }}</td>
              @empty
                  <tr>
                      <td colspan="4" style="text-align: center;">Aucun client inscrire</td>
                  </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
@endsection
