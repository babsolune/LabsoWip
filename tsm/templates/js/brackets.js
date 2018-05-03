/**
 * Created by alicamargo on 23/05/15.
 */
(function($){
    var get_html_player = function(player){
            var winner = player.winner ? 'winner' : '';
            var ID = player.ID ? player.ID : '';
            var score = player.score ? player.score : '';

            var html_player = '       <div class="player ' + winner + ' player-' + ID + '" data-id="' + ID + '">';

            if( player.url ){
                html_player += '        <a target="_blank" rel="noopener noreferrer" class="name" href="' + player.url + '">';
                html_player += '           <span class="team-name team-link">' + player.name + '</span>';
                html_player += '        </a>';
            }else{
                html_player += '           <span class="team-name">' + player.name + '</span>';
            }

            if( player.score ){
                html_player += '        <span class="score">' + player.score + '</span>';
            }

            html_player += '       </div>';

            return html_player;
        },

        get_html_match = function(match){
            var html_match = '   <div class="match">';

            if(match.player1)
            html_match += get_html_player(match.player1);

            if(match.player2)
            html_match += get_html_player(match.player2);

            html_match += '   </div>';

            return html_match;
        },

        get_html_separator = function(r, total){

            var html_separator = '<div class="separator-brackets rd-' + r + '">';
            for( var s = 1; s <= total; s++ ){
                html_separator += '<div class="line"></div>';
            }
            html_separator += '</div>';

            return html_separator;

        },

        get_html_header = function(titles, max_brackets){

            var html_titles = '';

            html_titles += '<div class="brackets-header">';

            if( titles ){
                if ( Array.isArray(titles) ){

                    $.each( titles, function(i, title){
                        html_titles += '<div class="title">' + title + '</div>';
                    });

                }else{

                    for(i = 1; i <= max_brackets; i++){
                        if( i == max_brackets) html_titles += '<div class="title">Champion</div>';
                        else if( i == (max_brackets-1) && max_brackets > 2 ) html_titles += '<div class="title">Final</div>';
                        else if( i == (max_brackets-2) && max_brackets > 3 ) html_titles += '<div class="title">Semifinal</div>';
                        else html_titles += '<div class="title">Round ' + i + '</div>';
                    }
                }

                }

            html_titles += '</div>';

            return html_titles;
        },

        get_html_dates = function(dates, max_brackets){

            var html_dates = '';

            html_dates += '<div class="brackets-dates">';

            if( dates ){
                if ( Array.isArray(dates) ){

                    $.each( dates, function(i, date){
                        html_dates += '<div class="dates">' + date + '</div>';
                    });

                }

            }

            html_dates += '</div>';

            return html_dates;
        },

        get_html = function(rounds, titles, dates){
            var html  = '';

            html += get_html_header(titles, rounds.length);
            html += get_html_dates(dates, rounds.length);
            html += '<div class="container-brackets">';

            $.each(rounds, function(r, round){
                html += '<div class="round rd-' + (r + 1) + '">';

                $.each(round, function(r, match){
                    html += get_html_match( match );
                });

                html += '</div>';

                if( (rounds.length != (r+1)) ){
                    html += get_html_separator( (r + 1), round.length );
                }


            });
            html += '</div>';

            return html;
        },

        key = function(i){
            return ( 23 *  Math.pow( 2, (i-2) ) ) + ( 20 * Math.pow( 2, (i-3) ) ) + ( 40 * ( Math.pow( 2, (i-3) ) - 1) )
        },

        get_style_brackets = function(max_brackets){
            var css = '';
            max_brackets = max_brackets ? max_brackets : 0;
            for(i = 2; i <= max_brackets; i++){

                var mtl = i == 2 ? 23 + 9.5 : key(i) + 19.5;
                var mtr = i == 2 ? 23 - 1.5 : key(i) + 8.5;
                var mbp = (2 * mtr) + 40;
                var hl = (2 * mtl) + 40;

                css += '/*-- Round ' + i + ' --*/';
                css += '.container-brackets .round.rd-' + i + '{ margin-top: ' + mtr + 'px; }';
                css += '   .container-brackets .round.rd-' + i + ' .match .player{ margin-bottom: ' + mbp + 'px; }';

                css += '/*-- lines ' + i + ' --*/';
                css += '.container-brackets .separator-brackets.rd-' + i + ' .line{';
                css += '   height: ' + hl + 'px;';
                css += '   margin-bottom: ' + hl + 'px;';
                css += '}';
                css += '   .container-brackets .separator-brackets.rd-' + i + ' .line:first-child{ margin-top: ' + mtl + 'px; }';

            }

            return css;

        },

        get_width_container = function(max_brackets){
                return (max_brackets)*180 + (max_brackets-1)*80 - (max_brackets-1)*75;
        },

        get_height_container = function(max_brackets){
            return key(max_brackets+1);
        },

        set_style = function(styles, n_brackets){

            var css,
                head = document.head || document.getElementsByTagName('head')[0],
                style = document.createElement('style');

            css = ".left{float: left}";
            css += ".right{float: right}";
            css += ".brackets-header{";
            css += "    height: 30px;";
            css += "    width: " + get_width_container( n_brackets ) + "px !important;";
            css += "}";
            css += ".brackets-header .title{";
            css += "    color: " + styles.color_title + " !important;";
            css += "}";
            css += ".brackets-header .title{ float: left; width: 180px;  margin-right: 5px; text-align: center;}";
            css += ".brackets-header .title:last-child{ margin-right: 0px;}";
            css += ".brackets-dates{";
            css += "    margin-bottom: 5px;";
            css += "    height: 30px;";
            css += "    width: " + get_width_container( n_brackets ) + "px !important;";
            css += "}";
            css += ".brackets-dates .dates{";
            css += "    color: " + styles.color_title + " !important;";
            css += "}";
            css += ".brackets-dates .dates{ float: left; width: 180px;  margin-right: 5px; text-align: center;}";
            css += ".brackets-dates .dates:last-child{ margin-right: 0px;}";
            css += ".container-brackets *,";
            css += ".container-brackets *:before,";
            css += ".container-brackets *:after {";
            css += "  -webkit-box-sizing: content-box !important;";
            css += "     -moz-box-sizing: content-box !important;";
            css += "          box-sizing: content-box !important;";
            css += "}";
            css += ".container-brackets{";
            css += "    position: relative;";
            css += "    overflow: hidden;";
            css += "    margin: 10px;";
            css += "    width: " + get_width_container( n_brackets ) + "px !important;";
            css += "    height: " + get_height_container( n_brackets ) + "px !important;";
            css += "}";
            css += ".container-brackets .round{ width: 180px; margin-left: -75px; float: left;}";
            css += "    .container-brackets .round:first-child{ margin-left: 0; }";
            css += "    .container-brackets .round .match{}";
            css += "        .container-brackets .round .match:last-child{ margin-bottom: 0px;}";
            css += "        .container-brackets .round .match .player{";
            css += "            border: solid 1px " + styles.border_color + ";";
            css += "            border-radius: " + styles.border_radius_player + ";";
            css += "            height: 21px;";
            css += "            /*width: calc(100% - 10px);*/";
            css += "            line-height: 21px;";
            css += "            background: " + styles.bg_player + ";";
            css += "        }";
            css += "            .container-brackets .round .match .player .team-name{";
            css += "                padding: 0 5px;";
            css += "            }";
            css += "            .container-brackets .round .match .player .team-name.team-link{";
            css += "                text-decoration: underline;";
            css += "            }";
            css += "            .container-brackets .round .match .player.hover{ background: " + styles.bg_player_hover + "; color: " + styles.color_player_hover + ";}";
            css += "                .container-brackets .round .match .player .name{";
            css += "                    text-align: center;";
            css += "                    text-decoration: none;";
            css += "                    cursor: pointer;";
            css += "                    width: 100%;";
            css += "                    color: " + styles.color_player + ";";
            css += "                }";
            css += "                .container-brackets .round .match .player .score{";
            css += "                    float: right;";
            css += "                    padding: 0 5px;";
            css += "                    border-radius: 0 " + styles.border_radius_player + "  " + styles.border_radius_player + " 0;";
            css += "                }";
            css += "                .container-brackets .round .match .player.winner .score{";
            css += "                    background-color: #bbffbb;";
            css += "                }";
            css += "                .container-brackets .round .match .player .score{";
            css += "                    background-color: #ffbbbb;";
            css += "                }";
            css += "                .container-brackets .round .match .player.hover a{";
            css += "                    color: " + styles.color_player_hover + " !important;";
            css += "                }";
            css += ".container-brackets .separator-brackets{ width: 80px; float: left;}";
            css += "    .container-brackets .separator-brackets .line{";
            css += "        border: 1px solid " + styles.border_color + ";";
            css += "        border-left: none;";
            css += "        border-radius: 0 " + styles.border_radius_lines + " " + styles.border_radius_lines + " 0;";
            css += "    }";
            css += "        .container-brackets .separator-brackets .line:last-child{ margin-bottom: 0; }";
            css += "/*-- First ronda --*/";
            css += "    .container-brackets .round.rd-1 .match{ margin-bottom: 40px; }";
            css += "        .container-brackets .round.rd-1 .match .player{ margin-bottom: 20px; }";
            css += "/*-- First lines --*/";
            css += ".container-brackets .separator-brackets.rd-1 .line{";
            css += "    height: 42px;";
            css += "    margin-bottom: 62px;";
            css += "}";
            css += "    .container-brackets .separator-brackets.rd-1 .line:first-child{ margin-top: 11px; }";

            css += get_style_brackets( n_brackets );

            style.type = 'text/css';

            if ( style.styleSheet ){
              style.styleSheet.cssText = css;
            } else {
              style.appendChild(document.createTextNode(css));
            }

            head.appendChild(style);

        };


    $.fn.brackets = function( options ){

        var opts = $.extend({}, $.fn.brackets.defaults, options);

        if( ! opts.rounds ){
            console.error("Round not found :(");
            return false;
        }

        if( this.length >= 1){
            this.each(function(){
                var $this = $(this);

                //-- add html brackets
                var container_brackets = get_html( opts.rounds, opts.titles, opts.dates )
                $this.html( container_brackets );
                set_style( opts, opts.rounds.length )

                //-- add event hover winner

                $('.player', $this).on( {
                    mouseover: function(){
                        var $this = $(this);
                        ID = $this.data('id');
                        $(".player[data-id='" + ID + "']").addClass('hover');
                        //  alert(ID)
                    },
                    mouseout: function(){
                        $(".player").removeClass('hover');
                    },

                });

            });
        }else{
            console.error('Object not found :( ');
        }

    };

    $.fn.brackets.defaults = {
        rounds: false,
        titles: false,
        dates: false,
        color_title: 'black',
        border_color: 'black',
        color_player: 'black',
        bg_player: 'white',
        color_player_hover: 'black',
        bg_player_hover: 'white',
        border_radius_player: '0px',
        border_radius_lines: '0px',
    };




})(jQuery);
