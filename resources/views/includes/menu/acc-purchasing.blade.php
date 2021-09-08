   @if(Auth::user()->dept_id == 1 || Auth::user()->dept_id == 2) 
    <li>
      <a href="{{ route('reports') }}" class="{{ route('reports') == url()->current() ? 'mm-active' : '' }}">
        <i class="metismenu-icon pe-7s-graph2"></i>
          Reports
      </a>
     </li>
    @endif
