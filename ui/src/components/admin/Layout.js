import { Component } from 'react';
import './../assets/admin/css/bootstrap.min.css'
import './../assets/admin/css/icons.min.css'
//import './../assets/admin/css/app.min.css'
// import './../assets/admin/css/custom.min.css'
//import 'https://maps.google.com/maps/api/js?key=AIzaSyCtSAR45TFgZjOs4nBFFZnII-6mMHLfSYI'


function Admin(props) {
    return (
        <div data-sidebar="dark" class="auth-body-bg">
            {/* @if (session('status')) <script> localStorage.setItem('sessionStatus', "{{ session('status') }}"); </script> @endif */}

            <div id="layout-wrapper">
                <Component element={props.content} />
            </div>

            {/* @include('cms.includes.footer') */}
            <Component element={props.sooter} />
        </div>
    );
}

export default Admin