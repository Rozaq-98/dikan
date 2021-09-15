<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		
		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu" data-widget="tree">
			{{-- get all menu master --}}
			@foreach ($allMenuMaster as $k_allMenuMaster)
				@for ($i = 0; $i < count(Session::get('ss_arrmaster')); $i++)
					{{-- get this menu master --}}
					@if($k_allMenuMaster->id == Session::get('ss_arrmaster')[$i])
						{{-- if tree == 0 --}}
						@if($k_allMenuMaster->tree == 0)
							<li>
								<a href="{{ url($k_allMenuMaster->url_master) }}">
									<i class="fa {{$k_allMenuMaster->fa_icon}}"></i> <span>{{$k_allMenuMaster->name}}</span>
								</a>
							</li>
						{{-- if tree == 1 --}}
						@else 
							<li class="treeview">
								<a href="#">
								<i class="fa {{$k_allMenuMaster->fa_icon}}"></i>
								<span>{{$k_allMenuMaster->name}}</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
								</a>
								<ul class="treeview-menu">
									{{-- get all menu detail --}}
									@foreach ($allMenuDetail as $k_allMenuDetail)
										@for ($j = 0; $j < count(Session::get('ss_arrdetail')); $j++)
											{{-- get menu detail where this menu master --}}
											@if($k_allMenuDetail->id_menu_master == Session::get('ss_arrmaster')[$i])
												{{-- get this menu detail --}}
												@if($k_allMenuDetail->id == Session::get('ss_arrdetail')[$j])
													<li>
														<a href="{{ url($k_allMenuMaster->url_master.$k_allMenuDetail->url_detail) }}">
															<i class="fa fa-circle-o"></i> {{$k_allMenuDetail->detail_name}}
														</a>
													</li>
												@endif
											@endif
										@endfor
									@endforeach
								</ul>
							</li>
						@endif
					@endif
				@endfor
			@endforeach
		</ul>
	</section>
	<!-- /.sidebar -->
</aside>