<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
      
           
      <li class="nav-item">
        <a href="{{route('manager.dashboard')}}" class="nav-link {{ (request()->is('manager/dashboard*')) ? 'active' : '' }}">
          <i class="nav-icon fas fa-th"></i>
          <p>
            Dashboard
          </p>
        </a>
      </li>

      <li class="nav-item">
        <a href="{{route('manager.addclient')}}" class="nav-link {{ (request()->is('manager/add-new-client*')) ? 'active' : '' }}">
          <i class="nav-icon fas fa-th"></i>
          <p>
            Create Client
          </p>
        </a>
      </li>

      <li class="nav-item">
        <a href="{{route('manager.newClient')}}" class="nav-link {{ (request()->is('manager/new-clients*')) ? 'active' : '' }}">
          <i class="nav-icon fas fa-th"></i>
          <p>
            New Client
          </p>
        </a>
      </li>

      <li class="nav-item">
        <a href="{{route('manager.processingclient')}}" class="nav-link {{ (request()->is('manager/processing-clients*')) ? 'active' : '' }}">
          <i class="nav-icon fas fa-th"></i>
          <p>
            Processing Client
          </p>
        </a>
      </li>

      <li class="nav-item">
        <a href="{{route('manager.completedclient')}}" class="nav-link {{ (request()->is('manager/completed-clients*')) ? 'active' : '' }}">
          <i class="nav-icon fas fa-th"></i>
          <p>
            Complete Client
          </p>
        </a>
      </li>

      <li class="nav-item">
        <a href="{{route('manager.declineclient')}}" class="nav-link {{ (request()->is('manager/decline-clients*')) ? 'active' : '' }}">
          <i class="nav-icon fas fa-th"></i>
          <p>
            Decline Client
          </p>
        </a>
      </li>

      
      <li class="nav-item">
        <a href="{{route('manager.client')}}" class="nav-link {{ (request()->is('manager/client*')) ? 'active' : '' }}">
          <i class="nav-icon fas fa-th"></i>
          <p>
            All Client
          </p>
        </a>
      </li>

      <li class="nav-item">
        <a href="{{route('agent.profile')}}" class="nav-link {{ (request()->is('manager/profile*')) ? 'active' : '' }}">
          <i class="nav-icon fas fa-th"></i>
          <p>
            Settings
          </p>
        </a>
      </li>

      
      {{-- <li class="nav-item {{ (request()->is('manager/client*')) ? 'menu-open' : '' }}{{ (request()->is('manager/completed-clients*')) ? 'menu-open' : '' }}{{ (request()->is('manager/decline-clients*')) ? 'menu-open' : '' }}{{ (request()->is('manager/processing-clients*')) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ (request()->is('manager/client*')) ? 'active' : '' }}">
          <i class="nav-icon fas fa-copy"></i>
          <p>
            Clients
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          
          <li class="nav-item">
            <a href="{{route('manager.client')}}" class="nav-link {{ (request()->is('manager/client*')) ? 'active' : '' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>All Clients</p>
            </a>
          </li>

        </ul>
      </li> --}}





    </ul>
  </nav>