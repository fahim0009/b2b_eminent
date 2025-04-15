<nav class="mt-2 mb-5">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->
    
         
    <li class="nav-item">
      <a href="{{route('admin.dashboard')}}" class="nav-link {{ (request()->is('admin/dashboard*')) ? 'active' : '' }}">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Dashboard
        </p>
      </a>
    </li>



    

    
    {{-- <li class="nav-item">
      <a href="{{route('admin.ksaProcessingClient')}}" class="nav-link {{ (request()->is('admin/ksa-processing-client*')) ? 'active' : '' }}">
        <i class="nav-icon fas fa-th"></i>
        <p>
          KSA Processing
        </p>
      </a>
    </li> --}}
    
    

    
    




    







    






    







  </ul>
</nav>