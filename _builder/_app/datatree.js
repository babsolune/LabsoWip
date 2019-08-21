(function(e, t, n, r) {
    function u(t, n) {
        t.find("[data-tree-branch]").each(function() {
            $me = e(this);
            $me.hide();
            var r = e(this).attr("data-tree-branch");
            var i = "";
            var s = 0;
            if (r.indexOf(n.delimeter) >= 0) {
                var o = r.split(n.delimeter);
                o.length = o.length - 1;
                i = o.join(n.delimeter);
                s = o.length;
            }
            $me.addClass("data-tree-level" + s);
            $me.attr("data-tree-parent", i);
            $me.attr("data-tree-open", 0);
            if (t.find('[data-tree-branch="' + i + '"]').attr("data-tree-open") == 1 || i == "") {
                $me.show();
            } else {
                $me.hide();
            }
            if (t.find('[data-tree-branch^="' + r + '"]').length == 1) {
                $me.addClass(n.endCSS);
            } else {
                $me.addClass(n.closedCSS);
            }
            if (n.opened.indexOf(r) >= 0) {
                a(t, r, n);
            }
        });
        t.on("click", "[data-tree-click]", function() {
            f(t, e(this).attr("data-tree-click"), n);
        });
    }

    function a(e, t, n) {
        var r = e.find('[data-tree-branch="' + t + '"]');
        var i = r.attr("data-tree-parent");
        f(e, t, n);
        if (i != "") {
            a(e, i, n);
        }
    }

    function f(e, t, n) {
        var r = e.find('[data-tree-branch="' + t + '"]');
        var i = n.openCSS;
        var s = n.closedCSS;
        var o = 0;
        if (r.attr("data-tree-open") == 0) {
            o = 1;
            i = n.closedCSS;
            s = n.openCSS
        }
        r.attr("data-tree-open", o);
        r.removeClass(i);
        r.addClass(s);
        l(e, t, r.attr("data-tree-open"))
    }

    function l(t, n, r) {
        t.find('[data-tree-parent="' + n + '"]').each(function() {
            $me = e(this);
            r == 1 ? $me.show() : $me.hide();
            if (r == 1 && $me.attr("data-tree-open") == 1 || r == 0) {
                n = $me.attr("data-tree-branch");
                l(t, n, r)
            }
        })
    }
    var i, s, o;
    e.fn.dataTree = function(t) {
        var n = e.extend({
            delimeter: ".", // separate parent from children
            openCSS: "dtv-open", // added class when a branch is opened
            closedCSS: "dtv-closed", // added class when a branch is closed
            endCSS: "dtv-end",
            opened: [] // What you want to be opened a start of the page (eg: 'myList.01', 'myList.04')
        }, t);
        return this.each(function() {
            $tree = e(this);
            if ($tree.length) {
                u($tree, n)
            }
        })
    }
})(jQuery)
