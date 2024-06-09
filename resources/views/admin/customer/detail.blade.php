<?php $title = 'Rincian Pelanggan'; ?>
@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'sales',
    'nav_active' => 'customer',
])

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header" style="padding:0;border-bottom:0;">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Info Pelanggan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="true">Riwayat Transaksi</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tab3-tab" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="true">Riwayat Servis</a>
            </li>
          </ul>
        </div>
        <div class="tab-content card-body" id="myTabContent">
          <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
            <div class="row">
              <div class="col-lg-4">
                <table class="table info table-striped">
                  <tr>
                    <td style="width:5%;">Kode</td>
                    <td style="width:1%;">:</td>
                    <td>{{ $item->idFormatted() }}</td>
                  </tr>
                  <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $item->name }}</td>
                  </tr>
                  <tr>
                    <td>Telepon</td>
                    <td>:</td>
                    <td>{{ $item->phone }}</td>
                  </tr>
                  <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $item->address }}</td>
                  </tr>
                  <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>{{ $item->active ? 'Aktif' : 'Non Aktif' }}</td>
                  </tr>
                  <tr>
                    <td>Catatan</td>
                    <td>:</td>
                    <td>{!! nl2br(e($item->notes)) !!}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
            TAB 2
          </div>
          <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
            TAB 3
          </div>
        </div>
      </div>
    </div>
  </div>
@endSection
