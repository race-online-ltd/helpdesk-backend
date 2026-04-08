<?php

/*
|--------------------------------------------------------------------------
| Load The Cached Routes
|--------------------------------------------------------------------------
|
| Here we will decode and unserialize the RouteCollection instance that
| holds all of the route information for an application. This allows
| us to instantaneously load the entire route map into the router.
|
*/

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/api/documentation' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'l5-swagger.default.api',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/oauth2-callback' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'l5-swagger.default.oauth2_callback',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/sanctum/csrf-cookie' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'sanctum.csrf-cookie',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/_ignition/health-check' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ignition.healthCheck',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/_ignition/execute-solution' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ignition.executeSolution',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/_ignition/update-config' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ignition.updateConfig',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/user/userauthentication' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::lPzmpOpUXqE5SBed',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/sms-attributes' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::XOZdu9pwqGkCYaXN',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Ox85uXUIJT0YXFyG',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/sms/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::st1defYXDVNJFznB',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/sms/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::rrVWknELBepmM3DS',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/sms-templates/check-exclude-notify' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::lOzTzyJgdCEbhN3Y',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/sla-client-configs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BjbsuFAU6m9k3bf2',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Le2Kl9fJetw3Ycx5',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/merge' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2AmnfKJmg3LKMRKH',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/sla-subcat-configs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Pc6GNTFwkBAxrhDR',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::OMyaSeEXXspZ34eP',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/send-sms-test' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::9KdUs9Nvdntgk7c4',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/send-sms' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6PAdPt68HmD9SCzI',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/send-sms-by-sid' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::T3t651jpVOewGRjn',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/send-sms-partner' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::d9ksKdzZymdj6UWa',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/send-sms-by-partner-number' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::d0BDrMPrPAPSzfX2',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/sms/send-client' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HkO5aPcJxMRYJReO',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/company/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YTFXkNfpIg3hoOJ3',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/company/cliententityshow' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::UhcPbCHQ343BL7ZX',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/company/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3n110PmVyVv7pyUw',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/department/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::JqrMfGzA0u3Ib68k',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/department/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4lc5A14OEdqQ0aOt',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/team-mapping' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::83htorDSTOuaZbnH',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/team-mapping/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ajnxcKTbuAuxPgBr',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/team/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::C84iPNQwhGfoG3Ye',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/team/all' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BidWZBibQgV4z8T2',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/team/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qiRW9RueWO2YVeq0',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/team/additional-config' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::WgkXf8Enuwgha701',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/division/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::CnOxK7wIWhRQjXkR',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/division/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::nYvwTmlfqMTLRWOX',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/category/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::lrXZUP9M8rPhIhOU',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/category/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FCb9INJlN0fTf8Q5',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/category/showall' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::hVJzUvdHduOwSaLV',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/category/visibility' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::7EchmVoNPPbVrzzs',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/subcategory/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aEkiInovxpa9Z8Y7',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/subcategory/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Za7zeu71N91f7xeW',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/subcategory/showall' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2TpZkysRBxKTPRtS',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/subcategory/visibility' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YAOQIBgJLvfdL01v',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/email/attribute' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::SaOcaWL6kyedIIpx',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/email/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::SQFjnobXjVaGzeL9',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/email/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::p2It1uSeuhZ7Thh8',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/email/notification/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::rzDjXQCKax5aACd1',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/email/notification/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::eMx9xaG4CGCgFYCX',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/sla/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3V3P45Jz1bLULAEj',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/sla/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yLaecBbWVp6xNS2r',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/role/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BXHGsWhKeZEzmqiF',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/role/default-client' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::bUTh1FHmWxjWPH5G',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/role/default-agent' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Qqp8taweHdcCjXux',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/role/showsidebaritems' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::kgBH60VqaKAyrGOp',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/role/showdashboarditems' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::KNmPWMaq545QLVIz',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/role/showsettingitems' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::M2dvcJuIRHi0bTNZ',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/role/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::iluEA9MCcDcly2sM',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/user/userfetch' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::lffvVOIro3Usz5BR',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/user/userlogout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::WaGWAbFHsVERNlKX',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/user/userregister' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::fzlwiig4EyC9Dug5',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/user/change-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2qxD0VI0ydGmXIYA',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/client/by-default-entity' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::IsiSwCOp6O8XmvHQ',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/client/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::e6NlDLlanLUxNx9v',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/client/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3uxEuv2KMO3uSP4L',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/client/entity/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2bu87m4mFKv8kz8e',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/agent/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::oIzx8hrFe3IC9NlI',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/agent/options' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::WRPjmzcIgV27UJ9L',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/agent/show-all' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::d59P5y5HraRCttZx',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/network-backbone/element/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Lxmo7TZTB09TKiTA',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/network-backbone/elements' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::dJwKiXOTZB6KeCoU',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/network-backbone/store/element' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::51t41EAL7bShRHCg',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/network-backbone/store/element-list' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::x8E7jDgD7igYwLVa',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/branch/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6oGKAxUxb5onYrbC',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/branch/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PcgTCvGKxFun1Fgo',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/utility/source/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::QnHfvq7WfEXqSZou',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/utility/status/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::o8qKDH09bFTuS1Y6',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/utility/cc-email/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xHaFVUOq7hYUT4Hy',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/utility/priority/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::WcDgMGMY6EWh4pnY',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/utility/reset-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Ad2nFCOtU96fTFYT',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/utility/client-reset-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::pEyjAQVjYSkj2f0c',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/utility/sid/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mz4cV9aCGSCypmPM',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/utility/fetch-and-insert-users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zTJMej59e8ARDL8Q',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/procedure' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Kl3nJXZQuT0UzHEq',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1MzTXLErn3p7zsmf',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/show/count' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ZczseRvIP7pfJUW6',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/show/by/status/entity' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yf2akpbif56ok6os',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gLGtu7YlyJZZOljV',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/store/self/ticket' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::nunEUTG5yafDHcLL',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/forward-to-hq' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ia9j9Cha8EV842cb',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/self/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xoG80HOg7hvOrbpY',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/self-ticket-to-ticket' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6Nbmnmk3eoyIfX4o',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/comment/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Vy28iPQGhAGRHRaN',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/assign/team/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::EJWzdmiLxsyI1mi6',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/notification/comment/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HaWRsyn1fFDr16LU',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/backbone-elements' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gSCRxAEgFOku6ast',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/get-selected-client-id' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::VUILaHV4zfpFEV7S',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/get-open-ticket-for-sid' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::oHccYWnzWno4MYkp',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/recently-open-and-closed-by' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::sOK4QJ9w7SRkil10',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/get-ticket-list-for-merge' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::P1PXiEB7XTeGFXYi',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/merge-tickets' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::u9v00PYMiU85DocK',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/districts' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Dybn45qB0yoc9fp2',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/divisions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qQ4BI79ya161HXUq',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/aggregators' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::eBzs5VkkucClX9rz',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/branches' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::B778Sngom5IyI4FH',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/agents' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::tUCpwxHY8UDgpbCz',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/ticket/events' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FXbnQsN5W1cLAU8M',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/reports/statistics/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ONxP9xUW0rYqpGZl',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/reports/life/cycle/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::f9q95SIeHotAumuw',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/reports/top/complaint/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::EAmDoA1iV2q6C123',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/reports/agent/performance/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::pSYyCJNUg081odIY',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/reports/sla/violation/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::CuHvNg3BJTK1EtMO',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/reports/ticket-details' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::U4SOju1ftI2Jp16z',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/reports/new-details' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wxQoy3PpDPSTzqcc',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/reports/agent-performance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xke9OaW89s8Mgm8u',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/reports/agent-performance-details' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::d0gfXgVqgzsQBzqt',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/summary/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::R1sloyB8XYXTLVr6',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/last/thirty/days' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0VunjnYYdBwxy6dC',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/statistics/show/by/department' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::umLR1cds7xhETNY2',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/statistics/show/by/division' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::QDA8ZtgIdGEYwuSQ',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/statistics/show/by/team' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::75liqcZLE57X151s',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/statistics/subcategory-vs-created/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::eDEpZ0E9goSF8I8A',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/statistics/graph/by/team' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Y05ry6CHA7y5QO3o',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/all-business-entity-open-ticket' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wvOFyGnLm1zF15xT',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/business-entity-ticket-summary' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::29GoHotozFthOqjn',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/ticket-count-details' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::JSRKKg6tqjcxbWqB',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/ticket-customer-client-wise' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mg10d6qIsvAPowlh',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/ticket-count-breakdown/team-wise' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::uIcs6WZXMHVQITQl',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/ticket-count-breakdown/team-wise-own' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::hsPvhzvZ9jclwVVl',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/ticket-count/entity-wise-own' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zHdm97fAhzJ3AMCe',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/team-vs-business-entity-ticket-count-details' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::AljYbHDOlRHDRQZe',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/team-vs-business-entity-ticket-count-details-by-business-entity-id' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wcjKfRdczq20VZQH',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/team-vs-own-business-entity-ticket-count-details' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::7NvKBsClKuSNnfAr',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/dashboard/sla-statistics-team-wise' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::CJMEYbobj8H4eWqW',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/prismerp/dhakacolo/customers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jnLXdvcR5LI5Bcfg',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/prismerp/earth/customers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BDABZFs7oe7f8SMc',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/prismerp/race/customers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::9oa79DaHzNrZDfVm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/aggregator/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::14iVLqEa2InFLEMq',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/aggregator/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::EFsym0iubiecmzpD',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/client-aggregator-mapping/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6cjTfr33yQyClwus',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/client-aggregator-mapping/show' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Wv6xrJvXXANWvKyG',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/settings/client-aggregator-mapping/list' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::a60QiBnE3PaQyY5r',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/super-app/priorities' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::O6SRrXGouQkk4Bb6',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/super-app/tickets' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6ducNQKn1R8c9N6f',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/super-app/filter-tickets' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FXgAgfoTpySSl5rm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/test-sms' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::trPEjkBD3r08FW5H',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/clear-cache' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::CL6ExJ9c2llouU8t',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/test-redis' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Xf9iWdOiUyr934Jk',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::guWjM4mDpUmvVnLJ',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/frsla' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Vaa1KYDT0ESbo9dH',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/clientsrvsla' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::l4FY1CnprvvqDyme',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/subcatsrvsla' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::i2QoIM0KY9JGkdAw',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dhakacolo/customers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PpFxqN1JUEQlM7vq',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/earth/customers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wWmoRH9FtZKozB2z',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/race/customers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Q1b1C5y1uUY9d6I6',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/token' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::QYxWBl69oa8YO5rE',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/apitest' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gSCquFMesuJnrYzy',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/getreport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::dzrw0TihxC9q7fNu',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/getreport2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YJifIBihDxogT30T',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/getdashboardmiddle' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qPAFqw7R1mqdNZ46',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/ticketstatistics' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::vqkHKKwi9CpinoIK',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/summarytest' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::N2AvFkXPPTIj9GTc',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/ticketstatdashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::kj62MjZl3xZRfMxj',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/getreportfinal' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::iIl9pUzy71QalTpe',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboardteamreport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FYszradRHVLDiDzE',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboarddivisionreport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::NTg4K6PsQBetk1VC',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboarddepartmentreport' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::olagMNZlgS4LYWj8',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tcketcycle' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::fkplotxzPkWdKZ1F',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/send-email' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::13KYSl4dr6359c8s',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/clientfrsla' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::pE8aD8NCBws0e03J',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/clientfresc' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::XlWbQ24GvTRMHAn9',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/clientsrvtimesla' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ETXvWeF74FTncEwF',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/clientsrvtimeesc' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::9JSoO8I7WrNToQRY',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/teamfrsla' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'teamfrsla',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/teamfresc' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'teamfresc',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/teamsrvtimesla' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'teamsrvtimesla',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/teamsrvtimeesc' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'teamsrvtimeesc',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/s' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8rKYJqa0HmviveKl',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/a' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Nn5vznlXRfdrTz9v',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/c' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mF9UqMjGgg8Nbez9',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/ticket-details' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ziCB8xgDaLy9i3Rw',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/test' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::fQIXAUnGmvr4FyoR',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/active' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::P1Xl43sDNhGPP0kO',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/test-event' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::TU2ICKJYCNV65QsO',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/clear-cache' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HN03HcxolsG8rpdI',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/docs(?|(?:/([^/]++))?(*:29)|/asset/([^/]++)(*:51))|/api/v1/(?|t(?|icket/(?|([^/]++)/sla\\-report(*:103)|open/by/team/([^/]++)(*:132)|c(?|lient\\-list/([^/]++)(*:164)|omment/show/by/ticket/([^/]++)(*:202))|s(?|how/([^/]++)(*:227)|tatus/change(?|/by/status/ticket/([^/]++)/([^/]++)/([^/]++)(*:294)|toopen/by/status/ticket/([^/]++)/([^/]++)/([^/]++)(*:352)))|is\\-ticket\\-real/([^/]++)(*:387)|de(?|tails/show/([^/]++)(*:419)|stroy/([^/]++)(*:441))|update/([^/]++)(*:465)|ticket\\-details\\-by\\-ticket/([^/]++)(*:509)|b(?|ranch\\-id/([^/]++)(*:539)|ackbone/element/list/by/([^/]++)(*:579))|assing/team/show/by/subcategory/([^/]++)(*:628)|violated/(?|first/response/time/sla/([^/]++)(*:680)|service/response/time/sla/([^/]++)(*:722)))|eam\\-mapping/(?|show/([^/]++)(*:761)|update/([^/]++)(*:784)|([^/]++)(*:800)|subcategory/(?|by\\-category/([^/]++)(*:844)|([^/]++)(*:860))|c(?|ategory/([^/]++)(*:889)|ompany/([^/]++)(*:912))))|p(?|ublic\\-ticket/([^/]++)(*:949)|rismerp/(?|dhakacolo/customers/([^/]++)(*:996)|earth/customers/([^/]++)(*:1028)|race/customers/([^/]++)(*:1060)))|s(?|ms\\-attributes/([^/]++)(?|(*:1101))|ettings/(?|s(?|ms/(?|show/([^/]++)(*:1145)|update/([^/]++)(*:1169))|ubcategory/(?|show(?|/(?|bycategory/([^/]++)/([^/]++)(*:1232)|([^/]++)(*:1249))|all\\-partner/([^/]++)/([^/]++)(*:1289))|update/([^/]++)(*:1314)|destroy/([^/]++)(*:1339))|la/(?|s(?|ubcategoryby(?|team(?|/([^/]++)/([^/]++)(*:1399)|new/([^/]++)/([^/]++)(*:1429))|businessentity/([^/]++)(*:1462))|how/(?|by/(?|subcategoryid/([^/]++)(*:1507)|team/([^/]++)(*:1529))|([^/]++)(*:1547)))|escalations/([^/]++)/([^/]++)/(?|edit(*:1595)|update(*:1610))|team(?|/([^/]++)/escalation/([^/]++)(*:1656)|s/([^/]++)/business\\-entity/([^/]++)/sub\\-categories(*:1717))|clients/show/by/business\\-entity/([^/]++)(*:1768)|update/([^/]++)(*:1792)|destroy/([^/]++)(*:1817)))|c(?|ompany/(?|show/([^/]++)(*:1855)|update/([^/]++)(*:1879)|destroy/([^/]++)(*:1904))|ategory/(?|de(?|fault\\-business\\-entity/([^/]++)(*:1962)|stroy/([^/]++)(*:1985))|u(?|nique\\-category/([^/]++)(*:2023)|pdate/([^/]++)(*:2046))|show(?|/([^/]++)(*:2072)|all\\-partner/([^/]++)(*:2102)))|lient(?|/(?|u(?|ser\\-serial/([^/]++)(*:2149)|pdate/([^/]++)(*:2172))|show/([^/]++)/entityId/([^/]++)(*:2213)|destroy/([^/]++)(*:2238))|\\-aggregator\\-mapping/(?|edit/([^/]++)(*:2286)|update/([^/]++)(*:2310)|delete/([^/]++)(*:2334))))|d(?|epartment/(?|show/([^/]++)(*:2376)|update/([^/]++)(*:2400)|destroy/([^/]++)(*:2425))|ivision/(?|show/([^/]++)(*:2459)|update/([^/]++)(*:2483)|destroy/([^/]++)(*:2508)))|team/(?|show/(?|by(?|subcategory/([^/]++)(*:2560)|/default/business/entity/([^/]++)(*:2602))|([^/]++)(*:2620))|update/([^/]++)(*:2645)|destroy/([^/]++)(*:2670)|config\\-show/([^/]++)(*:2700))|email/(?|show/([^/]++)(*:2732)|update/([^/]++)(*:2756)|destroy/([^/]++)(*:2781)|notification/(?|show/([^/]++)(*:2819)|update/([^/]++)(*:2843)|destroy/([^/]++)(*:2868)))|role/showpageitems/([^/]++)(*:2906)|ag(?|ent/(?|show/(?|([^/]++)(*:2943)|agents/by/team\\-of\\-default\\-business\\-entity/([^/]++)(*:3006)|byteam/([^/]++)(*:3030))|update/([^/]++)(*:3055))|gregator/(?|edit/([^/]++)(*:3090)|update/([^/]++)(*:3114)|delete/([^/]++)(*:3138)))|branch/(?|edit/([^/]++)(*:3172)|update/([^/]++)(*:3196)|show/([^/]++)(*:3218)))|la\\-(?|client\\-configs/([^/]++)(?|(*:3263))|subcat\\-configs/([^/]++)(?|(*:3300)))|uper\\-app/(?|companies/([^/]++)/business\\-divisions/([^/]++)/categories(?|(*:3385)|/([^/]++)/subcategories(*:3417))|tickets/([^/]++)(*:3443)))|client\\-aggregators/([^/]++)(*:3482)|reports/get\\-local\\-clients\\-by\\-business\\-entity/([^/]++)(*:3549)))/?$}sDu',
    ),
    3 => 
    array (
      29 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'l5-swagger.default.docs',
            'jsonFile' => NULL,
          ),
          1 => 
          array (
            0 => 'jsonFile',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      51 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'l5-swagger.default.asset',
          ),
          1 => 
          array (
            0 => 'asset',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      103 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::tbnizKpltHj1vZOr',
          ),
          1 => 
          array (
            0 => 'ticketNumber',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      132 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::oxmM2kNwwfadp2sM',
          ),
          1 => 
          array (
            0 => 'teamId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      164 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ga0QE6YMazbTka0R',
          ),
          1 => 
          array (
            0 => 'businessEntityId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      202 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::p0xAWwDYACCJKYJk',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      227 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5jEgK7awOtVTOE5L',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      294 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8CmWMGddyRyinwoz',
          ),
          1 => 
          array (
            0 => 'status',
            1 => 'ticketNo',
            2 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      352 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ZAtcbKnvwoajhqUN',
          ),
          1 => 
          array (
            0 => 'status',
            1 => 'ticketNo',
            2 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      387 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::K2xokjLXqbsPlNWl',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      419 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::R0TTwvOrvGSopXPl',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      441 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5iZ0vNNxa9K5ND5v',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      465 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YRG2AWOqwurjOdHT',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      509 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Bb2bdWLAoTcnkpn4',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      539 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5tjC0keV9nug9iip',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      579 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PiaFQn7PyBPObGHY',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      628 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0AsjHV3hxXRC7e6D',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      680 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FbmrxMdFgOKKuzpX',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      722 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ga7EDxtQ1APeSngH',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      761 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PptLb0G0AL82eXoY',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      784 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::auUoETRACHf7L12G',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      800 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::hyK9XbXvZ5lhM18U',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      844 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::AwQZt81T6UQYWPYM',
          ),
          1 => 
          array (
            0 => 'categoryId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      860 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Gums2LzeUCKmZ3aO',
          ),
          1 => 
          array (
            0 => 'subcategoryId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      889 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2wxWnOm4BWT5Na1e',
          ),
          1 => 
          array (
            0 => 'categoryId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      912 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::JRbUjfNWTtqUlsQ6',
          ),
          1 => 
          array (
            0 => 'companyId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      949 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yeOlh3X74Ye3tWnL',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      996 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::JXNOvlYc7o3a5WwA',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1028 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::XrdyNHtVkkK6g33M',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1060 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5lrdsLOob4WBYKl0',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1101 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mm95WwltJCIeWJ0z',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ll8cnepIhrMXWuls',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1145 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xRmsvs2ssRFM2ayo',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1169 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::R8tbwPsedmoxK6H1',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1232 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::9cdt2Bm3yWt5ofHk',
          ),
          1 => 
          array (
            0 => 'companyId',
            1 => 'categoryId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1249 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::19cCFOA0vCet8Xgf',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1289 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8RwnPFHwDkinWbB7',
          ),
          1 => 
          array (
            0 => 'categoryId',
            1 => 'entityId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1314 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5u74idn8c7TxDk2J',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1339 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::o87dmCl271zi2hzL',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1399 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::kRfdN2GEu2n6oVbV',
          ),
          1 => 
          array (
            0 => 'teamId',
            1 => 'businessEntity',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1429 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PvsnCSXB131XIRQb',
          ),
          1 => 
          array (
            0 => 'teamId',
            1 => 'businessEntity',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1462 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::GkPcY4CpqWiFZhGm',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1507 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::9coVJSpqiAtoWQO5',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1529 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5ry4o1cUgmHbCcwI',
          ),
          1 => 
          array (
            0 => 'teamId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1547 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ouJRzSqyOpdnsIkd',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1595 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::DznhFB9t7HeDAMct',
          ),
          1 => 
          array (
            0 => 'teamId',
            1 => 'levelId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1610 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mDTNtgw9oows2UNp',
          ),
          1 => 
          array (
            0 => 'teamId',
            1 => 'levelId',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1656 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::KZmmR4VFitM4wv0p',
          ),
          1 => 
          array (
            0 => 'teamId',
            1 => 'levelId',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1717 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::cZaj5OO0xztriQZo',
          ),
          1 => 
          array (
            0 => 'team_id',
            1 => 'business_entity_id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1768 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zr9ebiT5WHpnkjTy',
          ),
          1 => 
          array (
            0 => 'business_entity_id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1792 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::meASwve9jXR8dLeC',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1817 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::EjONPqyQc9m4uTGK',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1855 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::IwQUrb9a6Ry1jLOW',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1879 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::QdokeAkIFVm2wr0V',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1904 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::U4ll35VCbyO7rPHm',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1962 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::MZ14AoXiK40J406w',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1985 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::EYzDGBgLUhIdg80g',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2023 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wgOvdX9DbbKbrqIZ',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2046 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Lp2PdgyZZtRiBuEH',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2072 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jR7h5uTS3yLvS0Hv',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2102 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LqgXM5RFTg40sPsQ',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2149 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YFb3e68jBlAVCfXt',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2172 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::7lIWFTUgPMrdhrFT',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2213 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::S3psEHVTD2JHMXIs',
          ),
          1 => 
          array (
            0 => 'id',
            1 => 'businessId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2238 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yzqyeoqQnocrjK35',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2286 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::hWi9x9To64BuSKaN',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2310 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::UHWpdX72ExwCiEns',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2334 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yxg9UUDsbwIvjDa5',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2376 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::dLaoZkh7fG9ZGe3E',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2400 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mTf8oKTy6QUW5vt7',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2425 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LhCQN7aRWCYYIrWO',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2459 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::s0HMDCoVGE9OC007',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2483 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::TQJ6xejetU6HCNLa',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2508 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::iKZxF8fnyCVgExF5',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2560 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::sH0ESAJlU5EXwgya',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2602 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Z2Y14lAKZ4cQFzTS',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2620 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YxILu7djbNL21kHz',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2645 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qqp0aGtfvOVN55ft',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2670 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::iYwolVxBMPeqlZV0',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2700 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::7WnGUIa9GJqvSWYP',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2732 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::li6I0288rYIg6CEy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2756 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::sKww8Ta3NgzAdiLw',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2781 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8aWpyDeJ0uiuJMLH',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2819 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Cz5PUuyq3lqxVbUX',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2843 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::IXMQXw9GuAiu6Rq0',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2868 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::bDzGGPCMbFFEmV5u',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2906 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::SrcbeJZMI1L0DexS',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2943 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::azMkVbQngfxp62iv',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3006 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::AZGq1HI3gdFapHLQ',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3030 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::cvHTRt7WU2GfuaUn',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3055 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ieD8xgIUNfh9SORV',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3090 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Dnof8THKPVmlYhXH',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3114 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6FzCiBNXflBUcvWC',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3138 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::sC7XEcg0fizXF8mX',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3172 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6iycu7s4N0uUlzPP',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3196 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::oQyS3ITltyMRnD2O',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3218 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xgr2G07jMI274ryR',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3263 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wfOd73nFrmOrcRf5',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BnapYtf0KbHkGLW2',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6HMuAv02vKtx8nEG',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3300 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ldAztX8gyAUrpCfK',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Lmtl069PPiCfyKxZ',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'generated::WmaolleKYQMrhsyw',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3385 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::UYERt4bbpYjJnWe0',
          ),
          1 => 
          array (
            0 => 'company',
            1 => 'division',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3417 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::rNYZRO0gwyhNn2xB',
          ),
          1 => 
          array (
            0 => 'company',
            1 => 'division',
            2 => 'category',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3443 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2dp6Efqpg7fImk6V',
          ),
          1 => 
          array (
            0 => 'sid',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3482 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Buj1GyocPtWhZqga',
          ),
          1 => 
          array (
            0 => 'clientId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3549 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::cJ2kr8q3qd5WDIpl',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'l5-swagger.default.api' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/documentation',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'L5Swagger\\Http\\Middleware\\Config',
        ),
        'l5-swagger.documentation' => 'default',
        'as' => 'l5-swagger.default.api',
        'uses' => '\\L5Swagger\\Http\\Controllers\\SwaggerController@api',
        'controller' => '\\L5Swagger\\Http\\Controllers\\SwaggerController@api',
        'namespace' => 'L5Swagger',
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'l5-swagger.default.docs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'docs/{jsonFile?}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'L5Swagger\\Http\\Middleware\\Config',
        ),
        'l5-swagger.documentation' => 'default',
        'as' => 'l5-swagger.default.docs',
        'uses' => '\\L5Swagger\\Http\\Controllers\\SwaggerController@docs',
        'controller' => '\\L5Swagger\\Http\\Controllers\\SwaggerController@docs',
        'namespace' => 'L5Swagger',
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'l5-swagger.default.asset' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'docs/asset/{asset}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'L5Swagger\\Http\\Middleware\\Config',
        ),
        'l5-swagger.documentation' => 'default',
        'as' => 'l5-swagger.default.asset',
        'uses' => '\\L5Swagger\\Http\\Controllers\\SwaggerAssetController@index',
        'controller' => '\\L5Swagger\\Http\\Controllers\\SwaggerAssetController@index',
        'namespace' => 'L5Swagger',
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'l5-swagger.default.oauth2_callback' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/oauth2-callback',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'L5Swagger\\Http\\Middleware\\Config',
        ),
        'l5-swagger.documentation' => 'default',
        'as' => 'l5-swagger.default.oauth2_callback',
        'uses' => '\\L5Swagger\\Http\\Controllers\\SwaggerController@oauth2Callback',
        'controller' => '\\L5Swagger\\Http\\Controllers\\SwaggerController@oauth2Callback',
        'namespace' => 'L5Swagger',
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'sanctum.csrf-cookie' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sanctum/csrf-cookie',
      'action' => 
      array (
        'uses' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'controller' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'namespace' => NULL,
        'prefix' => 'sanctum',
        'where' => 
        array (
        ),
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'sanctum.csrf-cookie',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'ignition.healthCheck' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '_ignition/health-check',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'Spatie\\LaravelIgnition\\Http\\Middleware\\RunnableSolutionsEnabled',
        ),
        'uses' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\HealthCheckController@__invoke',
        'controller' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\HealthCheckController',
        'as' => 'ignition.healthCheck',
        'namespace' => NULL,
        'prefix' => '_ignition',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'ignition.executeSolution' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '_ignition/execute-solution',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'Spatie\\LaravelIgnition\\Http\\Middleware\\RunnableSolutionsEnabled',
        ),
        'uses' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\ExecuteSolutionController@__invoke',
        'controller' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\ExecuteSolutionController',
        'as' => 'ignition.executeSolution',
        'namespace' => NULL,
        'prefix' => '_ignition',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'ignition.updateConfig' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '_ignition/update-config',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'Spatie\\LaravelIgnition\\Http\\Middleware\\RunnableSolutionsEnabled',
        ),
        'uses' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\UpdateConfigController@__invoke',
        'controller' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\UpdateConfigController',
        'as' => 'ignition.updateConfig',
        'namespace' => NULL,
        'prefix' => '_ignition',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tbnizKpltHj1vZOr' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/{ticketNumber}/sla-report',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\SlaDetailsViewController@getTicketSlaReport',
        'controller' => 'App\\Http\\Controllers\\SlaDetailsViewController@getTicketSlaReport',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::tbnizKpltHj1vZOr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::lPzmpOpUXqE5SBed' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/user/userauthentication',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@Login',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@Login',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::lPzmpOpUXqE5SBed',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yeOlh3X74Ye3tWnL' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/public-ticket/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getPublicTicket',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getPublicTicket',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::yeOlh3X74Ye3tWnL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::XOZdu9pwqGkCYaXN' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/sms-attributes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SmsAttributeController@index',
        'controller' => 'App\\Http\\Controllers\\SmsAttributeController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/sms-attributes',
        'where' => 
        array (
        ),
        'as' => 'generated::XOZdu9pwqGkCYaXN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Ox85uXUIJT0YXFyG' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/sms-attributes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SmsAttributeController@store',
        'controller' => 'App\\Http\\Controllers\\SmsAttributeController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/sms-attributes',
        'where' => 
        array (
        ),
        'as' => 'generated::Ox85uXUIJT0YXFyG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mm95WwltJCIeWJ0z' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/sms-attributes/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SmsAttributeController@update',
        'controller' => 'App\\Http\\Controllers\\SmsAttributeController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/sms-attributes',
        'where' => 
        array (
        ),
        'as' => 'generated::mm95WwltJCIeWJ0z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ll8cnepIhrMXWuls' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/sms-attributes/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SmsAttributeController@destroy',
        'controller' => 'App\\Http\\Controllers\\SmsAttributeController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/sms-attributes',
        'where' => 
        array (
        ),
        'as' => 'generated::ll8cnepIhrMXWuls',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::st1defYXDVNJFznB' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/sms/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SmsTemplateController@index',
        'controller' => 'App\\Http\\Controllers\\SmsTemplateController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::st1defYXDVNJFznB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xRmsvs2ssRFM2ayo' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/sms/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SmsTemplateController@show',
        'controller' => 'App\\Http\\Controllers\\SmsTemplateController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::xRmsvs2ssRFM2ayo',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::rrVWknELBepmM3DS' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/sms/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SmsTemplateController@store',
        'controller' => 'App\\Http\\Controllers\\SmsTemplateController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::rrVWknELBepmM3DS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::R8tbwPsedmoxK6H1' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/sms/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SmsTemplateController@update',
        'controller' => 'App\\Http\\Controllers\\SmsTemplateController@update',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::R8tbwPsedmoxK6H1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::lOzTzyJgdCEbhN3Y' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/sms-templates/check-exclude-notify',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SmsTemplateController@checkExcludeNotify',
        'controller' => 'App\\Http\\Controllers\\SmsTemplateController@checkExcludeNotify',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::lOzTzyJgdCEbhN3Y',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BjbsuFAU6m9k3bf2' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/sla-client-configs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SlaClientConfigController@index',
        'controller' => 'App\\Http\\Controllers\\SlaClientConfigController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/sla-client-configs',
        'where' => 
        array (
        ),
        'as' => 'generated::BjbsuFAU6m9k3bf2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wfOd73nFrmOrcRf5' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/sla-client-configs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SlaClientConfigController@show',
        'controller' => 'App\\Http\\Controllers\\SlaClientConfigController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/sla-client-configs',
        'where' => 
        array (
        ),
        'as' => 'generated::wfOd73nFrmOrcRf5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Le2Kl9fJetw3Ycx5' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/sla-client-configs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SlaClientConfigController@store',
        'controller' => 'App\\Http\\Controllers\\SlaClientConfigController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/sla-client-configs',
        'where' => 
        array (
        ),
        'as' => 'generated::Le2Kl9fJetw3Ycx5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BnapYtf0KbHkGLW2' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/sla-client-configs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SlaClientConfigController@update',
        'controller' => 'App\\Http\\Controllers\\SlaClientConfigController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/sla-client-configs',
        'where' => 
        array (
        ),
        'as' => 'generated::BnapYtf0KbHkGLW2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6HMuAv02vKtx8nEG' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/sla-client-configs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SlaClientConfigController@destroy',
        'controller' => 'App\\Http\\Controllers\\SlaClientConfigController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/sla-client-configs',
        'where' => 
        array (
        ),
        'as' => 'generated::6HMuAv02vKtx8nEG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2AmnfKJmg3LKMRKH' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/merge',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\OpenTicketController@mergeTickets',
        'controller' => 'App\\Http\\Controllers\\OpenTicketController@mergeTickets',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::2AmnfKJmg3LKMRKH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Pc6GNTFwkBAxrhDR' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/sla-subcat-configs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SlaSubcatConfigController@index',
        'controller' => 'App\\Http\\Controllers\\SlaSubcatConfigController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/sla-subcat-configs',
        'where' => 
        array (
        ),
        'as' => 'generated::Pc6GNTFwkBAxrhDR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ldAztX8gyAUrpCfK' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/sla-subcat-configs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SlaSubcatConfigController@show',
        'controller' => 'App\\Http\\Controllers\\SlaSubcatConfigController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/sla-subcat-configs',
        'where' => 
        array (
        ),
        'as' => 'generated::ldAztX8gyAUrpCfK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::OMyaSeEXXspZ34eP' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/sla-subcat-configs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SlaSubcatConfigController@store',
        'controller' => 'App\\Http\\Controllers\\SlaSubcatConfigController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/sla-subcat-configs',
        'where' => 
        array (
        ),
        'as' => 'generated::OMyaSeEXXspZ34eP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Lmtl069PPiCfyKxZ' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/sla-subcat-configs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SlaSubcatConfigController@update',
        'controller' => 'App\\Http\\Controllers\\SlaSubcatConfigController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/sla-subcat-configs',
        'where' => 
        array (
        ),
        'as' => 'generated::Lmtl069PPiCfyKxZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WmaolleKYQMrhsyw' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/sla-subcat-configs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SlaSubcatConfigController@destroy',
        'controller' => 'App\\Http\\Controllers\\SlaSubcatConfigController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/sla-subcat-configs',
        'where' => 
        array (
        ),
        'as' => 'generated::WmaolleKYQMrhsyw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Buj1GyocPtWhZqga' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/client-aggregators/{clientId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ClientAggregatorMappingController@getAggregatorsByClient',
        'controller' => 'App\\Http\\Controllers\\ClientAggregatorMappingController@getAggregatorsByClient',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Buj1GyocPtWhZqga',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::oxmM2kNwwfadp2sM' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/open/by/team/{teamId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\OpenTicketController@getOpenTicketsByTeam',
        'controller' => 'App\\Http\\Controllers\\OpenTicketController@getOpenTicketsByTeam',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::oxmM2kNwwfadp2sM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9KdUs9Nvdntgk7c4' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/send-sms-test',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SendSmsController@sendSMSStatic',
        'controller' => 'App\\Http\\Controllers\\SendSmsController@sendSMSStatic',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::9KdUs9Nvdntgk7c4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6PAdPt68HmD9SCzI' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/send-sms',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SendSmsController@sendSMS',
        'controller' => 'App\\Http\\Controllers\\SendSmsController@sendSMS',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::6PAdPt68HmD9SCzI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::T3t651jpVOewGRjn' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/send-sms-by-sid',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SendSmsController@checkAndSendSMS',
        'controller' => 'App\\Http\\Controllers\\SendSmsController@checkAndSendSMS',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::T3t651jpVOewGRjn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::d9ksKdzZymdj6UWa' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/send-sms-partner',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SendSmsController@sendSMSForPartner',
        'controller' => 'App\\Http\\Controllers\\SendSmsController@sendSMSForPartner',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::d9ksKdzZymdj6UWa',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::d0BDrMPrPAPSzfX2' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/send-sms-by-partner-number',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SendSmsController@checkAndSendSMSForPartner',
        'controller' => 'App\\Http\\Controllers\\SendSmsController@checkAndSendSMSForPartner',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::d0BDrMPrPAPSzfX2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HkO5aPcJxMRYJReO' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/sms/send-client',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\SendSmsController@sendSMSForClient',
        'controller' => 'App\\Http\\Controllers\\SendSmsController@sendSMSForClient',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::HkO5aPcJxMRYJReO',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YTFXkNfpIg3hoOJ3' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/company/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CompanyController@index',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CompanyController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/company',
        'where' => 
        array (
        ),
        'as' => 'generated::YTFXkNfpIg3hoOJ3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::UhcPbCHQ343BL7ZX' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/company/cliententityshow',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CompanyController@ClientEntityShow',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CompanyController@ClientEntityShow',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/company',
        'where' => 
        array (
        ),
        'as' => 'generated::UhcPbCHQ343BL7ZX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3n110PmVyVv7pyUw' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/company/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CompanyController@store',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CompanyController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/company',
        'where' => 
        array (
        ),
        'as' => 'generated::3n110PmVyVv7pyUw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::IwQUrb9a6Ry1jLOW' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/company/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CompanyController@show',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CompanyController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/company',
        'where' => 
        array (
        ),
        'as' => 'generated::IwQUrb9a6Ry1jLOW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::QdokeAkIFVm2wr0V' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/company/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CompanyController@update',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CompanyController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/company',
        'where' => 
        array (
        ),
        'as' => 'generated::QdokeAkIFVm2wr0V',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::U4ll35VCbyO7rPHm' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/settings/company/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CompanyController@destroy',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CompanyController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/company',
        'where' => 
        array (
        ),
        'as' => 'generated::U4ll35VCbyO7rPHm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::JqrMfGzA0u3Ib68k' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/department/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\DepartmentController@index',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\DepartmentController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/department',
        'where' => 
        array (
        ),
        'as' => 'generated::JqrMfGzA0u3Ib68k',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4lc5A14OEdqQ0aOt' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/department/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\DepartmentController@store',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\DepartmentController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/department',
        'where' => 
        array (
        ),
        'as' => 'generated::4lc5A14OEdqQ0aOt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::dLaoZkh7fG9ZGe3E' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/department/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\DepartmentController@show',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\DepartmentController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/department',
        'where' => 
        array (
        ),
        'as' => 'generated::dLaoZkh7fG9ZGe3E',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mTf8oKTy6QUW5vt7' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/department/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\DepartmentController@update',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\DepartmentController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/department',
        'where' => 
        array (
        ),
        'as' => 'generated::mTf8oKTy6QUW5vt7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LhCQN7aRWCYYIrWO' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/settings/department/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\DepartmentController@destroy',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\DepartmentController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/department',
        'where' => 
        array (
        ),
        'as' => 'generated::LhCQN7aRWCYYIrWO',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::83htorDSTOuaZbnH' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/team-mapping',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\TeamMappingController@index',
        'controller' => 'App\\Http\\Controllers\\TeamMappingController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/team-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::83htorDSTOuaZbnH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ajnxcKTbuAuxPgBr' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/team-mapping/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\TeamMappingController@store',
        'controller' => 'App\\Http\\Controllers\\TeamMappingController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/team-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::ajnxcKTbuAuxPgBr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PptLb0G0AL82eXoY' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/team-mapping/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\TeamMappingController@show',
        'controller' => 'App\\Http\\Controllers\\TeamMappingController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/team-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::PptLb0G0AL82eXoY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::auUoETRACHf7L12G' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/team-mapping/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\TeamMappingController@update',
        'controller' => 'App\\Http\\Controllers\\TeamMappingController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/team-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::auUoETRACHf7L12G',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::hyK9XbXvZ5lhM18U' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/team-mapping/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\TeamMappingController@destroy',
        'controller' => 'App\\Http\\Controllers\\TeamMappingController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/team-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::hyK9XbXvZ5lhM18U',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::AwQZt81T6UQYWPYM' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/team-mapping/subcategory/by-category/{categoryId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\TeamMappingController@getSubCategoriesByCategory',
        'controller' => 'App\\Http\\Controllers\\TeamMappingController@getSubCategoriesByCategory',
        'namespace' => NULL,
        'prefix' => 'api/v1/team-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::AwQZt81T6UQYWPYM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2wxWnOm4BWT5Na1e' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/team-mapping/category/{categoryId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\TeamMappingController@getByCategoryId',
        'controller' => 'App\\Http\\Controllers\\TeamMappingController@getByCategoryId',
        'namespace' => NULL,
        'prefix' => 'api/v1/team-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::2wxWnOm4BWT5Na1e',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::JRbUjfNWTtqUlsQ6' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/team-mapping/company/{companyId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\TeamMappingController@getByCompanyId',
        'controller' => 'App\\Http\\Controllers\\TeamMappingController@getByCompanyId',
        'namespace' => NULL,
        'prefix' => 'api/v1/team-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::JRbUjfNWTtqUlsQ6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Gums2LzeUCKmZ3aO' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/team-mapping/subcategory/{subcategoryId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\TeamMappingController@getBySubcategoryId',
        'controller' => 'App\\Http\\Controllers\\TeamMappingController@getBySubcategoryId',
        'namespace' => NULL,
        'prefix' => 'api/v1/team-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::Gums2LzeUCKmZ3aO',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::C84iPNQwhGfoG3Ye' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/team/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@index',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/team',
        'where' => 
        array (
        ),
        'as' => 'generated::C84iPNQwhGfoG3Ye',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BidWZBibQgV4z8T2' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/team/all',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@all',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@all',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/team',
        'where' => 
        array (
        ),
        'as' => 'generated::BidWZBibQgV4z8T2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::sH0ESAJlU5EXwgya' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/team/show/bysubcategory/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@getTeamBySubcategory',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@getTeamBySubcategory',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/team',
        'where' => 
        array (
        ),
        'as' => 'generated::sH0ESAJlU5EXwgya',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qiRW9RueWO2YVeq0' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/team/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@store',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/team',
        'where' => 
        array (
        ),
        'as' => 'generated::qiRW9RueWO2YVeq0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Z2Y14lAKZ4cQFzTS' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/team/show/by/default/business/entity/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@getTeamByDefaultEntity',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@getTeamByDefaultEntity',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/team',
        'where' => 
        array (
        ),
        'as' => 'generated::Z2Y14lAKZ4cQFzTS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YxILu7djbNL21kHz' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/team/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@show',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/team',
        'where' => 
        array (
        ),
        'as' => 'generated::YxILu7djbNL21kHz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qqp0aGtfvOVN55ft' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/team/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@update',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/team',
        'where' => 
        array (
        ),
        'as' => 'generated::qqp0aGtfvOVN55ft',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::iYwolVxBMPeqlZV0' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/settings/team/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@destroy',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/team',
        'where' => 
        array (
        ),
        'as' => 'generated::iYwolVxBMPeqlZV0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WgkXf8Enuwgha701' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/team/additional-config',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@storeOrUpdateTeamConfiguration',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@storeOrUpdateTeamConfiguration',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/team',
        'where' => 
        array (
        ),
        'as' => 'generated::WgkXf8Enuwgha701',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::7WnGUIa9GJqvSWYP' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/team/config-show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@getTeamConfig',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\TeamController@getTeamConfig',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/team',
        'where' => 
        array (
        ),
        'as' => 'generated::7WnGUIa9GJqvSWYP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::CnOxK7wIWhRQjXkR' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/division/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\DivisionController@index',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\DivisionController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/division',
        'where' => 
        array (
        ),
        'as' => 'generated::CnOxK7wIWhRQjXkR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::nYvwTmlfqMTLRWOX' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/division/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\DivisionController@store',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\DivisionController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/division',
        'where' => 
        array (
        ),
        'as' => 'generated::nYvwTmlfqMTLRWOX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::s0HMDCoVGE9OC007' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/division/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\DivisionController@show',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\DivisionController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/division',
        'where' => 
        array (
        ),
        'as' => 'generated::s0HMDCoVGE9OC007',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::TQJ6xejetU6HCNLa' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/division/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\DivisionController@update',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\DivisionController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/division',
        'where' => 
        array (
        ),
        'as' => 'generated::TQJ6xejetU6HCNLa',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::iKZxF8fnyCVgExF5' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/settings/division/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\DivisionController@destroy',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\DivisionController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/division',
        'where' => 
        array (
        ),
        'as' => 'generated::iKZxF8fnyCVgExF5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::lrXZUP9M8rPhIhOU' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/category/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@index',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/category',
        'where' => 
        array (
        ),
        'as' => 'generated::lrXZUP9M8rPhIhOU',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::MZ14AoXiK40J406w' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/category/default-business-entity/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@fetchDefaultBusinessEntity',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@fetchDefaultBusinessEntity',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/category',
        'where' => 
        array (
        ),
        'as' => 'generated::MZ14AoXiK40J406w',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wgOvdX9DbbKbrqIZ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/category/unique-category/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@uniqueCategory',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@uniqueCategory',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/category',
        'where' => 
        array (
        ),
        'as' => 'generated::wgOvdX9DbbKbrqIZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FCb9INJlN0fTf8Q5' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/category/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@store',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/category',
        'where' => 
        array (
        ),
        'as' => 'generated::FCb9INJlN0fTf8Q5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jR7h5uTS3yLvS0Hv' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/category/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@show',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/category',
        'where' => 
        array (
        ),
        'as' => 'generated::jR7h5uTS3yLvS0Hv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Lp2PdgyZZtRiBuEH' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/category/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@update',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/category',
        'where' => 
        array (
        ),
        'as' => 'generated::Lp2PdgyZZtRiBuEH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EYzDGBgLUhIdg80g' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/settings/category/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@destroy',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/category',
        'where' => 
        array (
        ),
        'as' => 'generated::EYzDGBgLUhIdg80g',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::hVJzUvdHduOwSaLV' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/category/showall',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@fetchCategoryAll',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@fetchCategoryAll',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/category',
        'where' => 
        array (
        ),
        'as' => 'generated::hVJzUvdHduOwSaLV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LqgXM5RFTg40sPsQ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/category/showall-partner/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@fetchCategoryForPartner',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@fetchCategoryForPartner',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/category',
        'where' => 
        array (
        ),
        'as' => 'generated::LqgXM5RFTg40sPsQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::7EchmVoNPPbVrzzs' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/category/visibility',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@updateCategoryVisibility',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\CategoryController@updateCategoryVisibility',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/category',
        'where' => 
        array (
        ),
        'as' => 'generated::7EchmVoNPPbVrzzs',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9cdt2Bm3yWt5ofHk' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/subcategory/show/bycategory/{companyId}/{categoryId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@fetchSubcategoryByCategoryId',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@fetchSubcategoryByCategoryId',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/subcategory',
        'where' => 
        array (
        ),
        'as' => 'generated::9cdt2Bm3yWt5ofHk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aEkiInovxpa9Z8Y7' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/subcategory/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@index',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/subcategory',
        'where' => 
        array (
        ),
        'as' => 'generated::aEkiInovxpa9Z8Y7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Za7zeu71N91f7xeW' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/subcategory/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@store',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/subcategory',
        'where' => 
        array (
        ),
        'as' => 'generated::Za7zeu71N91f7xeW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::19cCFOA0vCet8Xgf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/subcategory/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@show',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/subcategory',
        'where' => 
        array (
        ),
        'as' => 'generated::19cCFOA0vCet8Xgf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5u74idn8c7TxDk2J' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/subcategory/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@update',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/subcategory',
        'where' => 
        array (
        ),
        'as' => 'generated::5u74idn8c7TxDk2J',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::o87dmCl271zi2hzL' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/settings/subcategory/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@destroy',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/subcategory',
        'where' => 
        array (
        ),
        'as' => 'generated::o87dmCl271zi2hzL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2TpZkysRBxKTPRtS' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/subcategory/showall',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@fetchSubcategoryAll',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@fetchSubcategoryAll',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/subcategory',
        'where' => 
        array (
        ),
        'as' => 'generated::2TpZkysRBxKTPRtS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8RwnPFHwDkinWbB7' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/subcategory/showall-partner/{categoryId}/{entityId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@fetchSubcategoryAllForPartner',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@fetchSubcategoryAllForPartner',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/subcategory',
        'where' => 
        array (
        ),
        'as' => 'generated::8RwnPFHwDkinWbB7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YAOQIBgJLvfdL01v' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/subcategory/visibility',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@updateSubCategoryVisibility',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\SubCategoryController@updateSubCategoryVisibility',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/subcategory',
        'where' => 
        array (
        ),
        'as' => 'generated::YAOQIBgJLvfdL01v',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::SaOcaWL6kyedIIpx' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/email/attribute',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\EmailController@getAttributes',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\EmailController@getAttributes',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/email',
        'where' => 
        array (
        ),
        'as' => 'generated::SaOcaWL6kyedIIpx',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::SQFjnobXjVaGzeL9' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/email/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\EmailController@index',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\EmailController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/email',
        'where' => 
        array (
        ),
        'as' => 'generated::SQFjnobXjVaGzeL9',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::p2It1uSeuhZ7Thh8' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/email/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\EmailController@store',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\EmailController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/email',
        'where' => 
        array (
        ),
        'as' => 'generated::p2It1uSeuhZ7Thh8',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::li6I0288rYIg6CEy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/email/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\EmailController@show',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\EmailController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/email',
        'where' => 
        array (
        ),
        'as' => 'generated::li6I0288rYIg6CEy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::sKww8Ta3NgzAdiLw' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/email/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\EmailController@update',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\EmailController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/email',
        'where' => 
        array (
        ),
        'as' => 'generated::sKww8Ta3NgzAdiLw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8aWpyDeJ0uiuJMLH' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/settings/email/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\EmailController@destroy',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\EmailController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/email',
        'where' => 
        array (
        ),
        'as' => 'generated::8aWpyDeJ0uiuJMLH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::rzDjXQCKax5aACd1' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/email/notification/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\NotificationController@index',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\NotificationController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/email/notification',
        'where' => 
        array (
        ),
        'as' => 'generated::rzDjXQCKax5aACd1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::eMx9xaG4CGCgFYCX' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/email/notification/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\NotificationController@store',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\NotificationController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/email/notification',
        'where' => 
        array (
        ),
        'as' => 'generated::eMx9xaG4CGCgFYCX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Cz5PUuyq3lqxVbUX' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/email/notification/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\NotificationController@show',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\NotificationController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/email/notification',
        'where' => 
        array (
        ),
        'as' => 'generated::Cz5PUuyq3lqxVbUX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::IXMQXw9GuAiu6Rq0' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/email/notification/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\NotificationController@update',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\NotificationController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/email/notification',
        'where' => 
        array (
        ),
        'as' => 'generated::IXMQXw9GuAiu6Rq0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::bDzGGPCMbFFEmV5u' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/settings/email/notification/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\NotificationController@destroy',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\NotificationController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/email/notification',
        'where' => 
        array (
        ),
        'as' => 'generated::bDzGGPCMbFFEmV5u',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3V3P45Jz1bLULAEj' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/sla/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@index',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::3V3P45Jz1bLULAEj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::kRfdN2GEu2n6oVbV' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/sla/subcategorybyteam/{teamId}/{businessEntity}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@getSubcategoryByTeam',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@getSubcategoryByTeam',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::kRfdN2GEu2n6oVbV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PvsnCSXB131XIRQb' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/sla/subcategorybyteamnew/{teamId}/{businessEntity}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@getSubcategoryByTeamNew',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@getSubcategoryByTeamNew',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::PvsnCSXB131XIRQb',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::GkPcY4CpqWiFZhGm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/sla/subcategorybybusinessentity/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@getSubcategoryByBusinessEntity',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@getSubcategoryByBusinessEntity',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::GkPcY4CpqWiFZhGm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9coVJSpqiAtoWQO5' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/sla/show/by/subcategoryid/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@getSLAbySubcategoryId',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@getSLAbySubcategoryId',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::9coVJSpqiAtoWQO5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5ry4o1cUgmHbCcwI' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/sla/show/by/team/{teamId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@showEscalation',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@showEscalation',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::5ry4o1cUgmHbCcwI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::DznhFB9t7HeDAMct' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/sla/escalations/{teamId}/{levelId}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@editEscalation',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@editEscalation',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::DznhFB9t7HeDAMct',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mDTNtgw9oows2UNp' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/sla/escalations/{teamId}/{levelId}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@updateEscalation',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@updateEscalation',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::mDTNtgw9oows2UNp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KZmmR4VFitM4wv0p' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/settings/sla/team/{teamId}/escalation/{levelId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@deleteEscalation',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@deleteEscalation',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::KZmmR4VFitM4wv0p',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cZaj5OO0xztriQZo' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/sla/teams/{team_id}/business-entity/{business_entity_id}/sub-categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@getSubCategoriesByTeam',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@getSubCategoriesByTeam',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::cZaj5OO0xztriQZo',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zr9ebiT5WHpnkjTy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/sla/clients/show/by/business-entity/{business_entity_id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@getClientsByBusinessEntity',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@getClientsByBusinessEntity',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::zr9ebiT5WHpnkjTy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yLaecBbWVp6xNS2r' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/sla/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@store',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::yLaecBbWVp6xNS2r',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ouJRzSqyOpdnsIkd' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/sla/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@edit',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@edit',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::ouJRzSqyOpdnsIkd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::meASwve9jXR8dLeC' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/sla/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@update',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::meASwve9jXR8dLeC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EjONPqyQc9m4uTGK' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/settings/sla/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@destroy',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ServiceLevelAgreementController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/sla',
        'where' => 
        array (
        ),
        'as' => 'generated::EjONPqyQc9m4uTGK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BXHGsWhKeZEzmqiF' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/role/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@index',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/role',
        'where' => 
        array (
        ),
        'as' => 'generated::BXHGsWhKeZEzmqiF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::bUTh1FHmWxjWPH5G' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/role/default-client',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@defaultClientRole',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@defaultClientRole',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/role',
        'where' => 
        array (
        ),
        'as' => 'generated::bUTh1FHmWxjWPH5G',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Qqp8taweHdcCjXux' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/role/default-agent',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@defaultAgentRole',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@defaultAgentRole',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/role',
        'where' => 
        array (
        ),
        'as' => 'generated::Qqp8taweHdcCjXux',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::kgBH60VqaKAyrGOp' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/role/showsidebaritems',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@fetchSidebarItems',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@fetchSidebarItems',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/role',
        'where' => 
        array (
        ),
        'as' => 'generated::kgBH60VqaKAyrGOp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KNmPWMaq545QLVIz' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/role/showdashboarditems',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@fetchDashboardItems',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@fetchDashboardItems',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/role',
        'where' => 
        array (
        ),
        'as' => 'generated::KNmPWMaq545QLVIz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::M2dvcJuIRHi0bTNZ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/role/showsettingitems',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@fetchSettingItems',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@fetchSettingItems',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/role',
        'where' => 
        array (
        ),
        'as' => 'generated::M2dvcJuIRHi0bTNZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::SrcbeJZMI1L0DexS' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/role/showpageitems/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@fetchPageDetails',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@fetchPageDetails',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/role',
        'where' => 
        array (
        ),
        'as' => 'generated::SrcbeJZMI1L0DexS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::iluEA9MCcDcly2sM' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/role/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@store',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\ViewItemsController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/role',
        'where' => 
        array (
        ),
        'as' => 'generated::iluEA9MCcDcly2sM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::lffvVOIro3Usz5BR' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/user/userfetch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@FetchUser',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@FetchUser',
        'namespace' => NULL,
        'prefix' => 'api/v1/user',
        'where' => 
        array (
        ),
        'as' => 'generated::lffvVOIro3Usz5BR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WaGWAbFHsVERNlKX' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/user/userlogout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@Logout',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@Logout',
        'namespace' => NULL,
        'prefix' => 'api/v1/user',
        'where' => 
        array (
        ),
        'as' => 'generated::WaGWAbFHsVERNlKX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::fzlwiig4EyC9Dug5' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/user/userregister',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@Register',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@Register',
        'namespace' => NULL,
        'prefix' => 'api/v1/user',
        'where' => 
        array (
        ),
        'as' => 'generated::fzlwiig4EyC9Dug5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2qxD0VI0ydGmXIYA' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/user/change-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@changePassword',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@changePassword',
        'namespace' => NULL,
        'prefix' => 'api/v1/user',
        'where' => 
        array (
        ),
        'as' => 'generated::2qxD0VI0ydGmXIYA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YFb3e68jBlAVCfXt' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/client/user-serial/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@getUserSerial',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@getUserSerial',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/client',
        'where' => 
        array (
        ),
        'as' => 'generated::YFb3e68jBlAVCfXt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::IsiSwCOp6O8XmvHQ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/client/by-default-entity',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@getClientsByDefaultEntity',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@getClientsByDefaultEntity',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/client',
        'where' => 
        array (
        ),
        'as' => 'generated::IsiSwCOp6O8XmvHQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::e6NlDLlanLUxNx9v' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/client/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@index',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/client',
        'where' => 
        array (
        ),
        'as' => 'generated::e6NlDLlanLUxNx9v',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3uxEuv2KMO3uSP4L' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/client/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@store',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/client',
        'where' => 
        array (
        ),
        'as' => 'generated::3uxEuv2KMO3uSP4L',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2bu87m4mFKv8kz8e' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/client/entity/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@insertClientNewEntity',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@insertClientNewEntity',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/client',
        'where' => 
        array (
        ),
        'as' => 'generated::2bu87m4mFKv8kz8e',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::S3psEHVTD2JHMXIs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/client/show/{id}/entityId/{businessId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@editClient',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@editClient',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/client',
        'where' => 
        array (
        ),
        'as' => 'generated::S3psEHVTD2JHMXIs',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::7lIWFTUgPMrdhrFT' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/client/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@updateClient',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@updateClient',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/client',
        'where' => 
        array (
        ),
        'as' => 'generated::7lIWFTUgPMrdhrFT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yzqyeoqQnocrjK35' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/settings/client/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@destroy',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/client',
        'where' => 
        array (
        ),
        'as' => 'generated::yzqyeoqQnocrjK35',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::oIzx8hrFe3IC9NlI' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/agent/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@getAgent',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@getAgent',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/agent',
        'where' => 
        array (
        ),
        'as' => 'generated::oIzx8hrFe3IC9NlI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WRPjmzcIgV27UJ9L' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/agent/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@getAgentOptions',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@getAgentOptions',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/agent',
        'where' => 
        array (
        ),
        'as' => 'generated::WRPjmzcIgV27UJ9L',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::azMkVbQngfxp62iv' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/agent/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@agentShow',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@agentShow',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/agent',
        'where' => 
        array (
        ),
        'as' => 'generated::azMkVbQngfxp62iv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::AZGq1HI3gdFapHLQ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/agent/show/agents/by/team-of-default-business-entity/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@getAgentByDefaultBusinessEntityTeam',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@getAgentByDefaultBusinessEntityTeam',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/agent',
        'where' => 
        array (
        ),
        'as' => 'generated::AZGq1HI3gdFapHLQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cvHTRt7WU2GfuaUn' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/agent/show/byteam/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@getAgentByTeam',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@getAgentByTeam',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/agent',
        'where' => 
        array (
        ),
        'as' => 'generated::cvHTRt7WU2GfuaUn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ieD8xgIUNfh9SORV' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/agent/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@update',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/agent',
        'where' => 
        array (
        ),
        'as' => 'generated::ieD8xgIUNfh9SORV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::d59P5y5HraRCttZx' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/agent/show-all',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@getAgentAll',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserRegisterController@getAgentAll',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/agent',
        'where' => 
        array (
        ),
        'as' => 'generated::d59P5y5HraRCttZx',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Lxmo7TZTB09TKiTA' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/network-backbone/element/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\NetworkBackboneController@showElements',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\NetworkBackboneController@showElements',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/network-backbone',
        'where' => 
        array (
        ),
        'as' => 'generated::Lxmo7TZTB09TKiTA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::dJwKiXOTZB6KeCoU' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/network-backbone/elements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\NetworkBackboneController@getNetworkBackboneElements',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\NetworkBackboneController@getNetworkBackboneElements',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/network-backbone',
        'where' => 
        array (
        ),
        'as' => 'generated::dJwKiXOTZB6KeCoU',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::51t41EAL7bShRHCg' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/network-backbone/store/element',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\NetworkBackboneController@storeElement',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\NetworkBackboneController@storeElement',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/network-backbone',
        'where' => 
        array (
        ),
        'as' => 'generated::51t41EAL7bShRHCg',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::x8E7jDgD7igYwLVa' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/network-backbone/store/element-list',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\NetworkBackboneController@storeElementList',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\NetworkBackboneController@storeElementList',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/network-backbone',
        'where' => 
        array (
        ),
        'as' => 'generated::x8E7jDgD7igYwLVa',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6oGKAxUxb5onYrbC' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/branch/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\BranchController@show',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\BranchController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/branch',
        'where' => 
        array (
        ),
        'as' => 'generated::6oGKAxUxb5onYrbC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PcgTCvGKxFun1Fgo' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/branch/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\BranchController@store',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\BranchController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/branch',
        'where' => 
        array (
        ),
        'as' => 'generated::PcgTCvGKxFun1Fgo',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6iycu7s4N0uUlzPP' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/branch/edit/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\BranchController@edit',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\BranchController@edit',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/branch',
        'where' => 
        array (
        ),
        'as' => 'generated::6iycu7s4N0uUlzPP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::oQyS3ITltyMRnD2O' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/branch/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\BranchController@update',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\BranchController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/branch',
        'where' => 
        array (
        ),
        'as' => 'generated::oQyS3ITltyMRnD2O',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xgr2G07jMI274ryR' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/branch/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\BranchController@getBranch',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\BranchController@getBranch',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/branch',
        'where' => 
        array (
        ),
        'as' => 'generated::xgr2G07jMI274ryR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::QnHfvq7WfEXqSZou' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/utility/source/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\UtilityController@getSource',
        'controller' => 'App\\Http\\Controllers\\v1\\UtilityController@getSource',
        'namespace' => NULL,
        'prefix' => 'api/v1/utility',
        'where' => 
        array (
        ),
        'as' => 'generated::QnHfvq7WfEXqSZou',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::o8qKDH09bFTuS1Y6' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/utility/status/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\UtilityController@getStatus',
        'controller' => 'App\\Http\\Controllers\\v1\\UtilityController@getStatus',
        'namespace' => NULL,
        'prefix' => 'api/v1/utility',
        'where' => 
        array (
        ),
        'as' => 'generated::o8qKDH09bFTuS1Y6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xHaFVUOq7hYUT4Hy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/utility/cc-email/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\UtilityController@getCcEmail',
        'controller' => 'App\\Http\\Controllers\\v1\\UtilityController@getCcEmail',
        'namespace' => NULL,
        'prefix' => 'api/v1/utility',
        'where' => 
        array (
        ),
        'as' => 'generated::xHaFVUOq7hYUT4Hy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WcDgMGMY6EWh4pnY' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/utility/priority/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\UtilityController@getPriority',
        'controller' => 'App\\Http\\Controllers\\v1\\UtilityController@getPriority',
        'namespace' => NULL,
        'prefix' => 'api/v1/utility',
        'where' => 
        array (
        ),
        'as' => 'generated::WcDgMGMY6EWh4pnY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Ad2nFCOtU96fTFYT' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/utility/reset-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\UtilityController@resetPassword',
        'controller' => 'App\\Http\\Controllers\\v1\\UtilityController@resetPassword',
        'namespace' => NULL,
        'prefix' => 'api/v1/utility',
        'where' => 
        array (
        ),
        'as' => 'generated::Ad2nFCOtU96fTFYT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::pEyjAQVjYSkj2f0c' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/utility/client-reset-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\UtilityController@clientResetPassword',
        'controller' => 'App\\Http\\Controllers\\v1\\UtilityController@clientResetPassword',
        'namespace' => NULL,
        'prefix' => 'api/v1/utility',
        'where' => 
        array (
        ),
        'as' => 'generated::pEyjAQVjYSkj2f0c',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mz4cV9aCGSCypmPM' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/utility/sid/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\UtilityController@getSID',
        'controller' => 'App\\Http\\Controllers\\v1\\UtilityController@getSID',
        'namespace' => NULL,
        'prefix' => 'api/v1/utility',
        'where' => 
        array (
        ),
        'as' => 'generated::mz4cV9aCGSCypmPM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zTJMej59e8ARDL8Q' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/utility/fetch-and-insert-users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\UtilityController@fetchAndInsert',
        'controller' => 'App\\Http\\Controllers\\v1\\UtilityController@fetchAndInsert',
        'namespace' => NULL,
        'prefix' => 'api/v1/utility',
        'where' => 
        array (
        ),
        'as' => 'generated::zTJMej59e8ARDL8Q',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Kl3nJXZQuT0UzHEq' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/procedure',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getTicketFromProcedures',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getTicketFromProcedures',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::Kl3nJXZQuT0UzHEq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ga0QE6YMazbTka0R' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/client-list/{businessEntityId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getClientList',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getClientList',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::ga0QE6YMazbTka0R',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1MzTXLErn3p7zsmf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@index',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::1MzTXLErn3p7zsmf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZczseRvIP7pfJUW6' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/show/count',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@ticketCount',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@ticketCount',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::ZczseRvIP7pfJUW6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yf2akpbif56ok6os' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/show/by/status/entity',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getTicketByStatusAndDefaultEntity',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getTicketByStatusAndDefaultEntity',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::yf2akpbif56ok6os',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gLGtu7YlyJZZOljV' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@store',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::gLGtu7YlyJZZOljV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5jEgK7awOtVTOE5L' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@show',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@show',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::5jEgK7awOtVTOE5L',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::K2xokjLXqbsPlNWl' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/is-ticket-real/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@isTicketReal',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@isTicketReal',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::K2xokjLXqbsPlNWl',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::R0TTwvOrvGSopXPl' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/details/show/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@ticketDetails',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@ticketDetails',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::R0TTwvOrvGSopXPl',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YRG2AWOqwurjOdHT' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/ticket/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@update',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::YRG2AWOqwurjOdHT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5iZ0vNNxa9K5ND5v' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/ticket/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@destroy',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::5iZ0vNNxa9K5ND5v',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::nunEUTG5yafDHcLL' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/store/self/ticket',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@storeSelfTicket',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@storeSelfTicket',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::nunEUTG5yafDHcLL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ia9j9Cha8EV842cb' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/forward-to-hq',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@forwardToHQ',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@forwardToHQ',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::ia9j9Cha8EV842cb',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xoG80HOg7hvOrbpY' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/self/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@selfTicketShow',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@selfTicketShow',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::xoG80HOg7hvOrbpY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6Nbmnmk3eoyIfX4o' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/self-ticket-to-ticket',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@insertSelfTicketToTicket',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@insertSelfTicketToTicket',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::6Nbmnmk3eoyIfX4o',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Bb2bdWLAoTcnkpn4' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/ticket-details-by-ticket/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getTicketDetailsByTicket',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getTicketDetailsByTicket',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::Bb2bdWLAoTcnkpn4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5tjC0keV9nug9iip' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/branch-id/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getBranchDetailsById',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getBranchDetailsById',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::5tjC0keV9nug9iip',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Vy28iPQGhAGRHRaN' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/comment/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@commentStore',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@commentStore',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::Vy28iPQGhAGRHRaN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::p0xAWwDYACCJKYJk' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/comment/show/by/ticket/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getCommentsByTicketNumber',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getCommentsByTicketNumber',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::p0xAWwDYACCJKYJk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8CmWMGddyRyinwoz' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/status/change/by/status/ticket/{status}/{ticketNo}/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@statusChanged',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@statusChanged',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::8CmWMGddyRyinwoz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EJWzdmiLxsyI1mi6' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/assign/team/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@assignTeamAndStore',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@assignTeamAndStore',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::EJWzdmiLxsyI1mi6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZAtcbKnvwoajhqUN' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/status/changetoopen/by/status/ticket/{status}/{ticketNo}/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@ticketReopened',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@ticketReopened',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::ZAtcbKnvwoajhqUN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0AsjHV3hxXRC7e6D' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/assing/team/show/by/subcategory/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getTeamBySubcategoryId',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getTeamBySubcategoryId',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::0AsjHV3hxXRC7e6D',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HaWRsyn1fFDr16LU' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/notification/comment/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@commentByUserTeam',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@commentByUserTeam',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::HaWRsyn1fFDr16LU',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FbmrxMdFgOKKuzpX' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/violated/first/response/time/sla/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@violatedFirstResponseTime',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@violatedFirstResponseTime',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::FbmrxMdFgOKKuzpX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ga7EDxtQ1APeSngH' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/violated/service/response/time/sla/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@violatedServiceResponseTime',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@violatedServiceResponseTime',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::ga7EDxtQ1APeSngH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gSCRxAEgFOku6ast' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/backbone-elements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\BackboneController@getAllBackboneElements',
        'controller' => 'App\\Http\\Controllers\\v1\\BackboneController@getAllBackboneElements',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::gSCRxAEgFOku6ast',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PiaFQn7PyBPObGHY' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/backbone/element/list/by/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\BackboneController@getBackboneElementListsByElementId',
        'controller' => 'App\\Http\\Controllers\\v1\\BackboneController@getBackboneElementListsByElementId',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::PiaFQn7PyBPObGHY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::VUILaHV4zfpFEV7S' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/get-selected-client-id',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getClientId',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getClientId',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::VUILaHV4zfpFEV7S',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::oHccYWnzWno4MYkp' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/get-open-ticket-for-sid',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getOpenTicketForSID',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getOpenTicketForSID',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::oHccYWnzWno4MYkp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::sOK4QJ9w7SRkil10' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/recently-open-and-closed-by',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getRecentlyOpenAndClosedTicketForSID',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getRecentlyOpenAndClosedTicketForSID',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::sOK4QJ9w7SRkil10',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::P1PXiEB7XTeGFXYi' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/get-ticket-list-for-merge',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@mergeTicketShowList',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@mergeTicketShowList',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::P1PXiEB7XTeGFXYi',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::u9v00PYMiU85DocK' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/ticket/merge-tickets',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@mergrTicketStore',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@mergrTicketStore',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::u9v00PYMiU85DocK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Dybn45qB0yoc9fp2' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/districts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getDistricts',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getDistricts',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::Dybn45qB0yoc9fp2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qQ4BI79ya161HXUq' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/divisions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getDivisions',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getDivisions',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::qQ4BI79ya161HXUq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::eBzs5VkkucClX9rz' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/aggregators',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getAggregators',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getAggregators',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::eBzs5VkkucClX9rz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::B778Sngom5IyI4FH' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/branches',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getBranches',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getBranches',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::B778Sngom5IyI4FH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tUCpwxHY8UDgpbCz' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/agents',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getAgents',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getAgents',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::tUCpwxHY8UDgpbCz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FXbnQsN5W1cLAU8M' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/ticket/events',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@fetchEvents',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@fetchEvents',
        'namespace' => NULL,
        'prefix' => 'api/v1/ticket',
        'where' => 
        array (
        ),
        'as' => 'generated::FXbnQsN5W1cLAU8M',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ONxP9xUW0rYqpGZl' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/reports/statistics/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@statisticsWithGraph',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@statisticsWithGraph',
        'namespace' => NULL,
        'prefix' => 'api/v1/reports',
        'where' => 
        array (
        ),
        'as' => 'generated::ONxP9xUW0rYqpGZl',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::f9q95SIeHotAumuw' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/reports/life/cycle/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@ticketLifeCycle',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@ticketLifeCycle',
        'namespace' => NULL,
        'prefix' => 'api/v1/reports',
        'where' => 
        array (
        ),
        'as' => 'generated::f9q95SIeHotAumuw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EAmDoA1iV2q6C123' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/reports/top/complaint/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@topComplaint',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@topComplaint',
        'namespace' => NULL,
        'prefix' => 'api/v1/reports',
        'where' => 
        array (
        ),
        'as' => 'generated::EAmDoA1iV2q6C123',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::pSYyCJNUg081odIY' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/reports/agent/performance/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@agentPerformance',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@agentPerformance',
        'namespace' => NULL,
        'prefix' => 'api/v1/reports',
        'where' => 
        array (
        ),
        'as' => 'generated::pSYyCJNUg081odIY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::CuHvNg3BJTK1EtMO' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/reports/sla/violation/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@slaViolation',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@slaViolation',
        'namespace' => NULL,
        'prefix' => 'api/v1/reports',
        'where' => 
        array (
        ),
        'as' => 'generated::CuHvNg3BJTK1EtMO',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::U4SOju1ftI2Jp16z' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/reports/ticket-details',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@getTicketDetails',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@getTicketDetails',
        'namespace' => NULL,
        'prefix' => 'api/v1/reports',
        'where' => 
        array (
        ),
        'as' => 'generated::U4SOju1ftI2Jp16z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cJ2kr8q3qd5WDIpl' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/reports/get-local-clients-by-business-entity/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@getLocalClientsByBusinessEntityId',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@getLocalClientsByBusinessEntityId',
        'namespace' => NULL,
        'prefix' => 'api/v1/reports',
        'where' => 
        array (
        ),
        'as' => 'generated::cJ2kr8q3qd5WDIpl',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wxQoy3PpDPSTzqcc' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/reports/new-details',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@getNewTicketReports',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@getNewTicketReports',
        'namespace' => NULL,
        'prefix' => 'api/v1/reports',
        'where' => 
        array (
        ),
        'as' => 'generated::wxQoy3PpDPSTzqcc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xke9OaW89s8Mgm8u' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/reports/agent-performance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@getAgentPerformance',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@getAgentPerformance',
        'namespace' => NULL,
        'prefix' => 'api/v1/reports',
        'where' => 
        array (
        ),
        'as' => 'generated::xke9OaW89s8Mgm8u',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::d0gfXgVqgzsQBzqt' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/reports/agent-performance-details',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@getAgentPerformanceDetails',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@getAgentPerformanceDetails',
        'namespace' => NULL,
        'prefix' => 'api/v1/reports',
        'where' => 
        array (
        ),
        'as' => 'generated::d0gfXgVqgzsQBzqt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::R1sloyB8XYXTLVr6' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/dashboard/summary/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@summary',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@summary',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::R1sloyB8XYXTLVr6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0VunjnYYdBwxy6dC' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/dashboard/last/thirty/days',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@dashboardLast30Days',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@dashboardLast30Days',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::0VunjnYYdBwxy6dC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::umLR1cds7xhETNY2' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/dashboard/statistics/show/by/department',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@departmentReportForDashboard',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@departmentReportForDashboard',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::umLR1cds7xhETNY2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::QDA8ZtgIdGEYwuSQ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/dashboard/statistics/show/by/division',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@divisionReportForDashboard',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@divisionReportForDashboard',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::QDA8ZtgIdGEYwuSQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::75liqcZLE57X151s' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/dashboard/statistics/show/by/team',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@teamReportForDashboard',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@teamReportForDashboard',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::75liqcZLE57X151s',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::eDEpZ0E9goSF8I8A' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/dashboard/statistics/subcategory-vs-created/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@subcategoryVsCreated',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@subcategoryVsCreated',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::eDEpZ0E9goSF8I8A',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Y05ry6CHA7y5QO3o' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/dashboard/statistics/graph/by/team',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@teamTicketDetailsGraph',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@teamTicketDetailsGraph',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::Y05ry6CHA7y5QO3o',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wvOFyGnLm1zF15xT' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/dashboard/all-business-entity-open-ticket',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getOpenTicketCountByBusinessEntity',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getOpenTicketCountByBusinessEntity',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::wvOFyGnLm1zF15xT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::29GoHotozFthOqjn' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/dashboard/business-entity-ticket-summary',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTicketSummaryByBusinessEntity',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTicketSummaryByBusinessEntity',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::29GoHotozFthOqjn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::JSRKKg6tqjcxbWqB' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/dashboard/ticket-count-details',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTeamTicketDetails',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTeamTicketDetails',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::JSRKKg6tqjcxbWqB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mg10d6qIsvAPowlh' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/dashboard/ticket-customer-client-wise',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTicketCountByClientCustomer',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTicketCountByClientCustomer',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::mg10d6qIsvAPowlh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uIcs6WZXMHVQITQl' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/dashboard/ticket-count-breakdown/team-wise',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTicketCountAndAvgTimeByTeam',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTicketCountAndAvgTimeByTeam',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::uIcs6WZXMHVQITQl',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::hsPvhzvZ9jclwVVl' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/dashboard/ticket-count-breakdown/team-wise-own',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTicketCountAndAvgTimeByTeamOwnEntity',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTicketCountAndAvgTimeByTeamOwnEntity',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::hsPvhzvZ9jclwVVl',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zHdm97fAhzJ3AMCe' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/dashboard/ticket-count/entity-wise-own',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTicketCountByOwnEntity',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTicketCountByOwnEntity',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::zHdm97fAhzJ3AMCe',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::AljYbHDOlRHDRQZe' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/dashboard/team-vs-business-entity-ticket-count-details',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTeamVsBusinessEntityTicketCountDetails',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTeamVsBusinessEntityTicketCountDetails',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::AljYbHDOlRHDRQZe',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wcjKfRdczq20VZQH' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/dashboard/team-vs-business-entity-ticket-count-details-by-business-entity-id',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTeamVsBusinessEntityTicketCountDetailsByBusinessEntityId',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTeamVsBusinessEntityTicketCountDetailsByBusinessEntityId',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::wcjKfRdczq20VZQH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::7NvKBsClKuSNnfAr' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/dashboard/team-vs-own-business-entity-ticket-count-details',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTeamVsOwnBusinessEntityTicketCountDetails',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTeamVsOwnBusinessEntityTicketCountDetails',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::7NvKBsClKuSNnfAr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::CJMEYbobj8H4eWqW' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/dashboard/sla-statistics-team-wise',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTeamWiseSLAstatistics',
        'controller' => 'App\\Http\\Controllers\\v1\\Dashboard\\DashboardController@getTeamWiseSLAstatistics',
        'namespace' => NULL,
        'prefix' => 'api/v1/dashboard',
        'where' => 
        array (
        ),
        'as' => 'generated::CJMEYbobj8H4eWqW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jnLXdvcR5LI5Bcfg' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/prismerp/dhakacolo/customers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@dhakaColoCustomers',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@dhakaColoCustomers',
        'namespace' => NULL,
        'prefix' => 'api/v1/prismerp',
        'where' => 
        array (
        ),
        'as' => 'generated::jnLXdvcR5LI5Bcfg',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::JXNOvlYc7o3a5WwA' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/prismerp/dhakacolo/customers/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@dhakaColoCustomerDetails',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@dhakaColoCustomerDetails',
        'namespace' => NULL,
        'prefix' => 'api/v1/prismerp',
        'where' => 
        array (
        ),
        'as' => 'generated::JXNOvlYc7o3a5WwA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BDABZFs7oe7f8SMc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/prismerp/earth/customers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@earthCustomers',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@earthCustomers',
        'namespace' => NULL,
        'prefix' => 'api/v1/prismerp',
        'where' => 
        array (
        ),
        'as' => 'generated::BDABZFs7oe7f8SMc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::XrdyNHtVkkK6g33M' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/prismerp/earth/customers/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@earthCustomerDetails',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@earthCustomerDetails',
        'namespace' => NULL,
        'prefix' => 'api/v1/prismerp',
        'where' => 
        array (
        ),
        'as' => 'generated::XrdyNHtVkkK6g33M',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9oa79DaHzNrZDfVm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/prismerp/race/customers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@raceCustomers',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@raceCustomers',
        'namespace' => NULL,
        'prefix' => 'api/v1/prismerp',
        'where' => 
        array (
        ),
        'as' => 'generated::9oa79DaHzNrZDfVm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5lrdsLOob4WBYKl0' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/prismerp/race/customers/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@raceCustomerDetails',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@raceCustomerDetails',
        'namespace' => NULL,
        'prefix' => 'api/v1/prismerp',
        'where' => 
        array (
        ),
        'as' => 'generated::5lrdsLOob4WBYKl0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::14iVLqEa2InFLEMq' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/aggregator/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\AggregatorController@store',
        'controller' => 'App\\Http\\Controllers\\AggregatorController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/aggregator',
        'where' => 
        array (
        ),
        'as' => 'generated::14iVLqEa2InFLEMq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EFsym0iubiecmzpD' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/aggregator/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\AggregatorController@index',
        'controller' => 'App\\Http\\Controllers\\AggregatorController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/aggregator',
        'where' => 
        array (
        ),
        'as' => 'generated::EFsym0iubiecmzpD',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Dnof8THKPVmlYhXH' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/aggregator/edit/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\AggregatorController@edit',
        'controller' => 'App\\Http\\Controllers\\AggregatorController@edit',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/aggregator',
        'where' => 
        array (
        ),
        'as' => 'generated::Dnof8THKPVmlYhXH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6FzCiBNXflBUcvWC' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/aggregator/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\AggregatorController@update',
        'controller' => 'App\\Http\\Controllers\\AggregatorController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/aggregator',
        'where' => 
        array (
        ),
        'as' => 'generated::6FzCiBNXflBUcvWC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::sC7XEcg0fizXF8mX' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/settings/aggregator/delete/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\AggregatorController@destroy',
        'controller' => 'App\\Http\\Controllers\\AggregatorController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/aggregator',
        'where' => 
        array (
        ),
        'as' => 'generated::sC7XEcg0fizXF8mX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6cjTfr33yQyClwus' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/settings/client-aggregator-mapping/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ClientAggregatorMappingController@store',
        'controller' => 'App\\Http\\Controllers\\ClientAggregatorMappingController@store',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/client-aggregator-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::6cjTfr33yQyClwus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Wv6xrJvXXANWvKyG' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/client-aggregator-mapping/show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ClientAggregatorMappingController@index',
        'controller' => 'App\\Http\\Controllers\\ClientAggregatorMappingController@index',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/client-aggregator-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::Wv6xrJvXXANWvKyG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::hWi9x9To64BuSKaN' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/client-aggregator-mapping/edit/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ClientAggregatorMappingController@edit',
        'controller' => 'App\\Http\\Controllers\\ClientAggregatorMappingController@edit',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/client-aggregator-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::hWi9x9To64BuSKaN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::UHWpdX72ExwCiEns' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/settings/client-aggregator-mapping/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ClientAggregatorMappingController@update',
        'controller' => 'App\\Http\\Controllers\\ClientAggregatorMappingController@update',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/client-aggregator-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::UHWpdX72ExwCiEns',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yxg9UUDsbwIvjDa5' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/settings/client-aggregator-mapping/delete/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ClientAggregatorMappingController@destroy',
        'controller' => 'App\\Http\\Controllers\\ClientAggregatorMappingController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/client-aggregator-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::yxg9UUDsbwIvjDa5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::a60QiBnE3PaQyY5r' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/settings/client-aggregator-mapping/list',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\ClientAggregatorMappingController@fetchAggregatorClientMapping',
        'controller' => 'App\\Http\\Controllers\\ClientAggregatorMappingController@fetchAggregatorClientMapping',
        'namespace' => NULL,
        'prefix' => 'api/v1/settings/client-aggregator-mapping',
        'where' => 
        array (
        ),
        'as' => 'generated::a60QiBnE3PaQyY5r',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::UYERt4bbpYjJnWe0' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/super-app/companies/{company}/business-divisions/{division}/categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Superapp\\SuperappController@categories',
        'controller' => 'App\\Http\\Controllers\\v1\\Superapp\\SuperappController@categories',
        'namespace' => NULL,
        'prefix' => 'api/v1/super-app',
        'where' => 
        array (
        ),
        'as' => 'generated::UYERt4bbpYjJnWe0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::rNYZRO0gwyhNn2xB' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/super-app/companies/{company}/business-divisions/{division}/categories/{category}/subcategories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Superapp\\SuperappController@subcategories',
        'controller' => 'App\\Http\\Controllers\\v1\\Superapp\\SuperappController@subcategories',
        'namespace' => NULL,
        'prefix' => 'api/v1/super-app',
        'where' => 
        array (
        ),
        'as' => 'generated::rNYZRO0gwyhNn2xB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::O6SRrXGouQkk4Bb6' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/super-app/priorities',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Superapp\\SuperappController@priorities',
        'controller' => 'App\\Http\\Controllers\\v1\\Superapp\\SuperappController@priorities',
        'namespace' => NULL,
        'prefix' => 'api/v1/super-app',
        'where' => 
        array (
        ),
        'as' => 'generated::O6SRrXGouQkk4Bb6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6ducNQKn1R8c9N6f' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/super-app/tickets',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Superapp\\SuperappController@storeTicket',
        'controller' => 'App\\Http\\Controllers\\v1\\Superapp\\SuperappController@storeTicket',
        'namespace' => NULL,
        'prefix' => 'api/v1/super-app',
        'where' => 
        array (
        ),
        'as' => 'generated::6ducNQKn1R8c9N6f',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2dp6Efqpg7fImk6V' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/super-app/tickets/{sid}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Superapp\\SuperappController@showTicket',
        'controller' => 'App\\Http\\Controllers\\v1\\Superapp\\SuperappController@showTicket',
        'namespace' => NULL,
        'prefix' => 'api/v1/super-app',
        'where' => 
        array (
        ),
        'as' => 'generated::2dp6Efqpg7fImk6V',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FXgAgfoTpySSl5rm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/super-app/filter-tickets',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Superapp\\SuperappController@filterTickets',
        'controller' => 'App\\Http\\Controllers\\v1\\Superapp\\SuperappController@filterTickets',
        'namespace' => NULL,
        'prefix' => 'api/v1/super-app',
        'where' => 
        array (
        ),
        'as' => 'generated::FXgAgfoTpySSl5rm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::trPEjkBD3r08FW5H' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/test-sms',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\UtilityController@sendSmsTest',
        'controller' => 'App\\Http\\Controllers\\v1\\UtilityController@sendSmsTest',
        'namespace' => NULL,
        'prefix' => 'api/v1',
        'where' => 
        array (
        ),
        'as' => 'generated::trPEjkBD3r08FW5H',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::CL6ExJ9c2llouU8t' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/clear-cache',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:194:"function () {
    // dd(\'check\');
    \\Illuminate\\Support\\Facades\\Artisan::call(\'cache:clear\');
    \\Illuminate\\Support\\Facades\\Artisan::call(\'optimize:clear\');
    return \\redirect()->back();
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000079f0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::CL6ExJ9c2llouU8t',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Xf9iWdOiUyr934Jk' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/test-redis',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:234:"function () {
    $key = \'test_key\';
    $value = \\Illuminate\\Support\\Facades\\Cache::remember($key, 60, function () {
        return \'This is a test value from Redis cache.\';
    });
    return \\response()->json([\'data\' => $value]);
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000007bd0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Xf9iWdOiUyr934Jk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::guWjM4mDpUmvVnLJ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:44:"function () {
    return \\view(\'welcome\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000008980000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::guWjM4mDpUmvVnLJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Vaa1KYDT0ESbo9dH' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'frsla',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@firstResponseSlaCheck',
        'controller' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@firstResponseSlaCheck',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::Vaa1KYDT0ESbo9dH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::l4FY1CnprvvqDyme' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'clientsrvsla',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@serviceTimeClientSlaCheck',
        'controller' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@serviceTimeClientSlaCheck',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::l4FY1CnprvvqDyme',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::i2QoIM0KY9JGkdAw' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'subcatsrvsla',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@serviceTimeSubCategorySlaCheck',
        'controller' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@serviceTimeSubCategorySlaCheck',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::i2QoIM0KY9JGkdAw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PpFxqN1JUEQlM7vq' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dhakacolo/customers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@dhakaColoCustomers',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@dhakaColoCustomers',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::PpFxqN1JUEQlM7vq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wWmoRH9FtZKozB2z' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'earth/customers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@earthCustomers',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@earthCustomers',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::wWmoRH9FtZKozB2z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Q1b1C5y1uUY9d6I6' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'race/customers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@raceCustomers',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@raceCustomers',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::Q1b1C5y1uUY9d6I6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::QYxWBl69oa8YO5rE' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'token',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@test',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@test',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::QYxWBl69oa8YO5rE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gSCquFMesuJnrYzy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'apitest',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@testApi',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@testApi',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::gSCquFMesuJnrYzy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::dzrw0TihxC9q7fNu' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'getreport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@reportsOutput',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@reportsOutput',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::dzrw0TihxC9q7fNu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YJifIBihDxogT30T' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'getreport2',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@reportsOutput2',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@reportsOutput2',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::YJifIBihDxogT30T',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qPAFqw7R1mqdNZ46' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'getdashboardmiddle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@statisticsWithGraph',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@statisticsWithGraph',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::qPAFqw7R1mqdNZ46',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::vqkHKKwi9CpinoIK' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'ticketstatistics',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@statistics',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@statistics',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::vqkHKKwi9CpinoIK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::N2AvFkXPPTIj9GTc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'summarytest',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@summaryTest',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@summaryTest',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::N2AvFkXPPTIj9GTc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::kj62MjZl3xZRfMxj' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'ticketstatdashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@statisticsWithGraph',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@statisticsWithGraph',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::kj62MjZl3xZRfMxj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::iIl9pUzy71QalTpe' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'getreportfinal',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@getReportsOutput',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@getReportsOutput',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::iIl9pUzy71QalTpe',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FYszradRHVLDiDzE' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboardteamreport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@teamReportForDashboard',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@teamReportForDashboard',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::FYszradRHVLDiDzE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::NTg4K6PsQBetk1VC' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboarddivisionreport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@divisionReportForDashboard',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@divisionReportForDashboard',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::NTg4K6PsQBetk1VC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::olagMNZlgS4LYWj8' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboarddepartmentreport',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@departmentReportForDashboard',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@departmentReportForDashboard',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::olagMNZlgS4LYWj8',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::fkplotxzPkWdKZ1F' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tcketcycle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@ticketLifeCycle',
        'controller' => 'App\\Http\\Controllers\\v1\\Report\\ReportsController@ticketLifeCycle',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::fkplotxzPkWdKZ1F',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::13KYSl4dr6359c8s' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'send-email',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\EmailController@sendEmailNotification',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\EmailController@sendEmailNotification',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::13KYSl4dr6359c8s',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::pE8aD8NCBws0e03J' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'clientfrsla',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@isViolatedClientFirstResponseTime',
        'controller' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@isViolatedClientFirstResponseTime',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::pE8aD8NCBws0e03J',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::XlWbQ24GvTRMHAn9' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'clientfresc',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@escalatedClientFirstResponseTime',
        'controller' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@escalatedClientFirstResponseTime',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::XlWbQ24GvTRMHAn9',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ETXvWeF74FTncEwF' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'clientsrvtimesla',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@isViolatedClientServiceTime',
        'controller' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@isViolatedClientServiceTime',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::ETXvWeF74FTncEwF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9JSoO8I7WrNToQRY' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'clientsrvtimeesc',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@escalatedClientServiceTime',
        'controller' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@escalatedClientServiceTime',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::9JSoO8I7WrNToQRY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'teamfrsla' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'teamfrsla',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@isViolatedTeamFirstResponseTime',
        'controller' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@isViolatedTeamFirstResponseTime',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'teamfrsla',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'teamfresc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'teamfresc',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@escalatedTeamFirstResponseTime',
        'controller' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@escalatedTeamFirstResponseTime',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'teamfresc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'teamsrvtimesla' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'teamsrvtimesla',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@isViolatedTeamServiceTime',
        'controller' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@isViolatedTeamServiceTime',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'teamsrvtimesla',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'teamsrvtimeesc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'teamsrvtimeesc',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@escalatedTeamServiceTime',
        'controller' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@escalatedTeamServiceTime',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'teamsrvtimeesc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8rKYJqa0HmviveKl' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 's',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@statusChanged',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@statusChanged',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::8rKYJqa0HmviveKl',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Nn5vznlXRfdrTz9v' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'a',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@assignTeam',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@assignTeam',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::Nn5vznlXRfdrTz9v',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mF9UqMjGgg8Nbez9' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'c',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@comment',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@comment',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::mF9UqMjGgg8Nbez9',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ziCB8xgDaLy9i3Rw' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'ticket-details',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getTicketDetails',
        'controller' => 'App\\Http\\Controllers\\v1\\Ticket\\TicketController@getTicketDetails',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::ziCB8xgDaLy9i3Rw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::fQIXAUnGmvr4FyoR' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'test',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@serviceTimeSubCategorySlaCheck',
        'controller' => 'App\\Http\\Controllers\\v1\\Corn\\SLAJobController@serviceTimeSubCategorySlaCheck',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::fQIXAUnGmvr4FyoR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::P1Xl43sDNhGPP0kO' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'active',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@active',
        'controller' => 'App\\Http\\Controllers\\v1\\Settings\\UserLoginController@active',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::P1Xl43sDNhGPP0kO',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::TU2ICKJYCNV65QsO' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'test-event',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:126:"function () {
    \\event(new \\App\\Events\\CommentEvent([\'message\' => \'Hello from Laravel!\']));
    return \'Event triggered!\';
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000008bc0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::TU2ICKJYCNV65QsO',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HN03HcxolsG8rpdI' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'clear-cache',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:108:"function () {
    \\Illuminate\\Support\\Facades\\Artisan::call(\'optimize:clear\');
    return "Cache Cleared";
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000008bf0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::HN03HcxolsG8rpdI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
