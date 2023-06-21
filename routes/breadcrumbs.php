<?php // routes/breadcrumbs.php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('admin.dashboard'));
});

// ------------ PROVINCE ------------
Breadcrumbs::for('province', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Provinsi', route('admin.province.index'));
});

// Province > Create
Breadcrumbs::for('province.create', function (BreadcrumbTrail $trail) {
    $trail->parent('province');
    $trail->push('Tambah Provinsi', route('admin.province.create'));
});

// ------------ REGENCY ------------
Breadcrumbs::for('regency', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Kabupaten/Kota', route('admin.regency.index'));
});

// Regency > Create
Breadcrumbs::for('regency.create', function (BreadcrumbTrail $trail) {
    $trail->parent('regency');
    $trail->push('Tambah Kabupaten/Kota', route('admin.regency.create'));
});

// ------------ DISTRICT ------------
Breadcrumbs::for('district', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Kecamatan', route('admin.district.index'));
});

// District > Create
Breadcrumbs::for('district.create', function (BreadcrumbTrail $trail) {
    $trail->parent('district');
    $trail->push('Tambah Kecamatan', route('admin.district.create'));
});

// ------------ TPA TYPE ------------
Breadcrumbs::for('tpa-type', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Jenis TPA', route('admin.tpa-type.index'));
});

// Tpa Type > Create
Breadcrumbs::for('tpa-type.create', function (BreadcrumbTrail $trail) {
    $trail->parent('tpa-type');
    $trail->push('Tambah Jenis TPA', route('admin.tpa-type.create'));
});

// Tpa Type > Edit
Breadcrumbs::for('tpa-type.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('tpa-type');
    $trail->push('Edit Jenis TPA', route('admin.tpa-type.edit', $data->id));
});

// ------------ FLOOR TYPE ------------
Breadcrumbs::for('floor-type', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Jenis Lantai', route('admin.floor-type.index'));
});

// Floor Type > Create
Breadcrumbs::for('floor-type.create', function (BreadcrumbTrail $trail) {
    $trail->parent('floor-type');
    $trail->push('Tambah Jenis Lantai', route('admin.floor-type.create'));
});

// Floor Type > Edit
Breadcrumbs::for('floor-type.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('floor-type');
    $trail->push('Edit Jenis Lantai', route('admin.floor-type.edit', $data->id));
});

// ------------ ENVIRONMENT TYPE ------------
Breadcrumbs::for('environment-type', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Jenis Lingkungan', route('admin.environment-type.index'));
});

// Environment Type > Create
Breadcrumbs::for('environment-type.create', function (BreadcrumbTrail $trail) {
    $trail->parent('environment-type');
    $trail->push('Tambah Jenis Lingkungan', route('admin.environment-type.create'));
});

// Environment Type > Edit
Breadcrumbs::for('environment-type.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('environment-type');
    $trail->push('Edit Jenis Lingkungan', route('admin.environment-type.edit', $data->id));
});

// ------------ VILLAGE TYPE ------------
Breadcrumbs::for('village', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Desa', route('admin.village.index'));
});

// Village > Create
Breadcrumbs::for('village.create', function (BreadcrumbTrail $trail) {
    $trail->parent('village');
    $trail->push('Tambah Desa', route('admin.village.create'));
});

// Village > Edit
Breadcrumbs::for('village.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('village');
    $trail->push('Edit Desa', route('admin.village.edit', $data->id));
});

// ------------ LOCATION TYPE ------------
Breadcrumbs::for('location-type', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Jenis Lokasi', route('admin.location-type.index'));
});

// Location Type > Create
Breadcrumbs::for('location-type.create', function (BreadcrumbTrail $trail) {
    $trail->parent('location-type');
    $trail->push('Tambah Jenis Lokasi', route('admin.location-type.create'));
});

// Location Type > Edit
Breadcrumbs::for('location-type.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('location-type');
    $trail->push('Edit Jenis Lokasi', route('admin.location-type.edit', $data->id));
});

// ------------ SETTLEMENT TYPE ------------
Breadcrumbs::for('settlement-type', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Jenis Permukiman', route('admin.settlement-type.index'));
});

// Settlement Type > Create
Breadcrumbs::for('settlement-type.create', function (BreadcrumbTrail $trail) {
    $trail->parent('settlement-type');
    $trail->push('Tambah Jenis Permukiman', route('admin.settlement-type.create'));
});

// Settlement Type > Edit
Breadcrumbs::for('settlement-type.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('settlement-type');
    $trail->push('Edit Jenis Permukiman', route('admin.settlement-type.edit', $data->id));
});

// ------------ BUILDING TYPE ------------
Breadcrumbs::for('building-type', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Jenis Bangunan', route('admin.building-type.index'));
});

// Building Type > Create
Breadcrumbs::for('building-type.create', function (BreadcrumbTrail $trail) {
    $trail->parent('building-type');
    $trail->push('Tambah Jenis Bangunan', route('admin.building-type.create'));
});

// Building Type > Edit
Breadcrumbs::for('building-type.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('building-type');
    $trail->push('Edit Jenis Bangunan', route('admin.building-type.edit', $data->id));
});

// ------------ SEROTYPE ------------
Breadcrumbs::for('serotype', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Serotipe', route('admin.serotype.index'));
});

// Serotype > Create
Breadcrumbs::for('serotype.create', function (BreadcrumbTrail $trail) {
    $trail->parent('serotype');
    $trail->push('Tambah Serotipe', route('admin.serotype.create'));
});

// Serotype > Edit
Breadcrumbs::for('serotype.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('serotype');
    $trail->push('Edit Serotipe', route('admin.serotype.edit', $data->id));
});

// ------------ VIRUS ------------
Breadcrumbs::for('virus', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Virus', route('admin.virus.index'));
});

// Virus > Create
Breadcrumbs::for('virus.create', function (BreadcrumbTrail $trail) {
    $trail->parent('virus');
    $trail->push('Tambah Virus', route('admin.virus.create'));
});

// Virus > Edit
Breadcrumbs::for('virus.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('virus');
    $trail->push('Edit Virus', route('admin.virus.edit', $data->id));
});

// ------------ MORPHOTYPE ------------
Breadcrumbs::for('morphotype', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Morfotipe', route('admin.morphotype.index'));
});

// Morphtype > Create
Breadcrumbs::for('morphotype.create', function (BreadcrumbTrail $trail) {
    $trail->parent('morphotype');
    $trail->push('Tambah Morfotipe', route('admin.morphotype.create'));
});

// Morphtype > Edit
Breadcrumbs::for('morphotype.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('morphotype');
    $trail->push('Edit Morfotipe', route('admin.morphotype.edit', $data->id));
});

// ------------ SAMPLE METHOD ------------
Breadcrumbs::for('sample-method', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Metode Pengambilan Sampel', route('admin.sample-method.index'));
});

// Sample Method > Create
Breadcrumbs::for('sample-method.create', function (BreadcrumbTrail $trail) {
    $trail->parent('sample-method');
    $trail->push('Tambah Metode Pengambilan Sampel', route('admin.sample-method.create'));
});

// Sample Method > Edit
Breadcrumbs::for('sample-method.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('sample-method');
    $trail->push('Edit Metode Pengambilan Sampel', route('admin.sample-method.edit', $data->id));
});

// ------------ SAMPLE ------------
Breadcrumbs::for('sample', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Sampel', route('admin.sample.index'));
});

// Sample > Create
Breadcrumbs::for('sample.create', function (BreadcrumbTrail $trail) {
    $trail->parent('sample');
    $trail->push('Tambah', route('admin.sample.create'));
});

// Sample > Edit
Breadcrumbs::for('sample.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('sample');
    $trail->push($data->sample_code, route('admin.sample.detail-sample', $data->id));
    $trail->push('Edit', route('admin.sample.edit', $data->id));
});

// Sample > Detail Sample
Breadcrumbs::for('sample.detail-sample', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('sample');
    $trail->push($data->sample_code, route('admin.sample.edit', $data->id));
    $trail->push('Detail', route('admin.sample.detail-sample', $data->id));
});

// Sample > Detail Sample > Virus
Breadcrumbs::for('sample.detail-sample.virus', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('sample.detail-sample', $data->sample);
    $trail->push($data->virus->name);
});

// ------------ VARIABLE AGENT ------------
Breadcrumbs::for('variable-agent', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Variabel Agen', route('admin.variable-agent.index'));
});

// Variable Agent > Show
Breadcrumbs::for('variable-agent.show', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('variable-agent');
    $trail->push($data->name, route('admin.variable-agent.show', $data->id));
});

// ------------ LARVAE ------------
Breadcrumbs::for('larvae', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Larva', route('admin.larvae.index'));
});

// Larvae > Create
Breadcrumbs::for('larvae.create', function (BreadcrumbTrail $trail) {
    $trail->parent('larvae');
    $trail->push('Tambah', route('admin.larvae.create'));
});

// Larvae > Show
Breadcrumbs::for('larvae.show', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('larvae');
    $trail->push($data->larva_code, route('admin.larvae.show', $data->id));
});

// Larvae > Edit
Breadcrumbs::for('larvae.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('larvae.show', $data);
    $trail->push('Edit', route('admin.larvae.edit', $data->id));
});

// Larvae > Detail > Create
Breadcrumbs::for('larvae.detail.create', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('larvae.show', $data);
    $trail->push('Tambah', route('admin.larvae.detail.create', $data->id));
});

// Larvae > Detail > Edit
Breadcrumbs::for('larvae.detail.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('larvae.show', $data);
    $trail->push('Ubah', route('admin.larvae.detail.edit', $data->id));
});

// ------------ KSH ------------
Breadcrumbs::for('ksh', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('KSH', route('admin.ksh.index'));
});

// KSH > Create
Breadcrumbs::for('ksh.create', function (BreadcrumbTrail $trail) {
    $trail->parent('ksh');
    $trail->push('Tambah', route('admin.ksh.create'));
});

// KSH > Show
Breadcrumbs::for('ksh.show', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('ksh');
    $trail->push('Detail', route('admin.ksh.show', $data->id));
});

// KSH > Edit
Breadcrumbs::for('ksh.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('ksh.show', $data);
    $trail->push('Edit', route('admin.ksh.edit', $data->id));
});

// KSH > Detail > Create
Breadcrumbs::for('ksh.detail.create', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('ksh.show', $data);
    $trail->push('Tambah', route('admin.ksh.detail.create', $data->id));
});

// KSH > Detail > Edit
Breadcrumbs::for('ksh.detail.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('ksh.show', $data->ksh);
    $trail->push('Ubah', route('admin.ksh.detail.edit', $data->id));
});

// KSH > Member
Breadcrumbs::for('ksh.member', function (BreadcrumbTrail $trail) {
    $trail->parent('ksh');
    $trail->push('Anggota', route('admin.ksh.member'));
});

// KSH > Member > Create
Breadcrumbs::for('ksh.member.create', function (BreadcrumbTrail $trail) {
    $trail->parent('ksh.member');
    $trail->push('Tambah', route('admin.ksh.member.create'));
});

// ------------ ABJ ------------
Breadcrumbs::for('abj', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('ABJ', route('admin.abj.index'));
});

// ------------ USER ------------
Breadcrumbs::for('user', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Pengguna', route('admin.user.index'));
});
