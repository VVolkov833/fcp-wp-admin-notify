<?php

/*
Plugin Name: FCP Admin Notify
The Popup with a notification for users, entered WordPress admin area
Version: 1.0.0
Requires at least: 5.0.0
Requires PHP: 7.0.0
Author: Firmcatalyst, Vadim Volkov
Author URI: https://firmcatalyst.com
License: GPL v3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

class FCPAadminNotify {

    public function __construct() {

        if ( !is_admin() ) {
            return;
        }
        add_action( 'admin_init', function() {

            add_action( 'admin_footer', [ $this, 'footerHTML' ] );
        
        });

	}
	
	public function footerHTML() {
?>
<!---------------------- -->
<div style="display:none;" id="admin-notify-wrap">
    <noindex>
        <div class="fcp-an-content">
            <h2 style="text-align:center;">Important!</h2>
            <p><strong>Please, don't edit anything till 20.01</strong>. I'm in process of adding new photos to the website Staging, which will be pushed right on Tuesday and override all the changes you might have added by then. If the changes are important, please notify me, what changes were performed.</p>
            <p style="text-align:right;"><em>Vadim from Firmcatalyst</em></p>
        </div>
    </noindex>
</div>
<style>
    .fcp-an-holder {
        display:block;
        position:fixed;
        left:0; top:0; right:0; bottom:0;
        z-index:-1;
        pointer-events:none;
        text-align:center;
        transition:z-index 0.72s linear;
    }
    .fcp-an-holder.fcp-an-active {
        z-index:9999997;
        pointer-events:auto;
        transition:z-index 0s linear;
    }
    .fcp-an-holder:before {
        content:'';
        display:inline-block;
        vertical-align:middle;
        width:1px;
        margin-left:-1px;
        height:100%;
    }
    .fcp-an-back {
        display:block;
        position:fixed;
        top:100%;
        right:0; bottom:0; left:0;
        z-index:9999998;
        overflow:hidden;
        background-color:rgba(0,0,0,0.8);
        transition:top 0.4s 0.32s ease;
    }
    .fcp-an-back:after {
        content:'';
        display:block;
        width:2.5vw;
        height:2.5vw;
        position:absolute;
        top:2.5vw;
        right:2.5vw;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' version='1.1' viewBox='0 0 10 10'%3E%3Cg fill='none' stroke='%23fff' stroke-linecap='round' stroke-width='1'%3E%3Cpath d='m9.6 9.6-4.6-4.6 4.6-4.6'/%3E%3Cpath d='m0.4 9.6 4.6-4.6-4.6-4.6'/%3E%3C/g%3E%3C/svg%3E%0A");
        background-repeat:no-repeat;
        background-position:right top;
        background-size:contain;
        opacity:0.4;
        filter:drop-shadow( 0px 2px 3px rgba(0,0,0,1) );
        cursor:pointer;
        transition:opacity 0.2s ease;
    }
    .fcp-an-back:hover:after {
        opacity:0.8;
    }
    .fcp-an-holder.fcp-an-active > .fcp-an-back {
        top:0;
        transition:top 0.4s ease;
    }

    .fcp-an {
        position:relative;
        z-index:9999999;
        display:inline-block;
        width:95%;
        min-width:310px;
        max-width:500px;
        max-height:95%;
        overflow-x:hidden;
        overflow-y:auto;
        vertical-align:middle;
        padding:22px;
        box-sizing:border-box;
        background-color:#fff;
        text-align:left;
        box-shadow: 0px 5px 20px 0px rgba(0,0,0,1);
        opacity:0;
        transition:opacity 0.3s ease;
    }
    @media (max-width:830px) {
        .fcp-an {
            margin-top:7.5%;
            max-height:87.5%;
        }
    }

    .fcp-an-holder.fcp-an-active > .fcp-an-back + .fcp-an {
        opacity:1;
        transition:opacity 0.3s 0.32s ease;
    }

    .fcp-an-ok {
        display: block;
        margin:30px 0 -10px;
        width: auto;
        padding: 7px 6px 6px;
        border: 1px solid #999;
        box-sizing: border-box;
        background-color: #999;
        color: #fff;
        border-radius: 3px;
        text-decoration: none;
        text-align:center;
        cursor:pointer;
        transition: background-color 0.2s ease, color 0.2s ease;
    }
    .fcp-an-ok:hover {
        background-color: #fff;
        color: #999;
    }

</style>
<script>
window.addEventListener( 'load', function() {
    var $ = jQuery;

    function vvNotify(content, ok, reopen) {

        $( 'body' ).append( '<div class="fcp-an-holder"><div class="fcp-an-back"></div><div class="fcp-an"></div></div>' );

        $( '.fcp-an-back' ).click( vvNotifyClose );
    
        if ( ok ) {
            content += '<p><span class="fcp-an-ok">'+ok+'</span></p>';
        }
        $( '.fcp-an' ).html( content );
        $( '.fcp-an-ok' ).click( vvNotifyClose );
        if ( !sessionStorage.getItem( 'fcp-an-closed' ) ) {
            $( '.fcp-an-holder' ).addClass( 'fcp-an-active' );
        }

        if ( reopen ) {
            $( '.fcp-an-holder' ).after( '<div class="fcp-an-reopen"><div class="fcp-an-reopen-title">'+reopen.title+'</div>'+reopen.content+'</div>' );
            $( '.fcp-an-reopen' ).click( function() {
                $( '.fcp-an-holder' ).addClass( 'fcp-an-active' );
            });
        }
    }

    function vvNotifyClose() {
        $( '.fcp-an-holder' ).removeClass( 'fcp-an-active' );
        sessionStorage.setItem( 'fcp-an-closed', 1 );
    }

    setTimeout( function() {
        vvNotify(
            $( '#admin-notify-wrap .fcp-an-content' ).html(),
            'OK',
            {
                "title" : $( '#admin-notify-wrap .reopen-title' ).html(),
                "content" : $( '#admin-notify-wrap .reopen-content' ).html()
            }
            );
    }, 500 );
});
</script>
<!------------/---------- -->
<?php
	}

}

new FCPAadminNotify();
