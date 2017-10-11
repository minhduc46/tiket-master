jQuery(function ($) {
    var center = $('#center'),
        scale = 3.5,
        container = $('#container'),
        parent = container.parent(),
        seat = container.find('circle').parent(),
        click = false,
        clickX = 0,
        clickY = 0,
        lastMoveX = 0,
        lastMoveY = 0,
        moveX = 0,
        moveY = 0,
        mouseMoveHandler = false,
        mouseUpHandler = false,
        zoomRatio = parent.parent().css('zoom');
    container.on('dblclick', function (e) {
        if (!parent.hasClass('zoomed')) {
            console.log(topY);
            e.stopPropagation();
            var x = center.position().left - (e.clientX / zoomRatio) - (window.scrollX / zoomRatio) + 100,
                y = center.position().top - (e.clientY / zoomRatio) - (window.scrollY / zoomRatio) + topY;
            parent.addClass('zoomed');
            parent.css('transform', 'scale(' + scale + ') translate(' + x + 'px,' + y + 'px)');
            parent.css('-webkit-transform', 'scale(' + scale + ') translate(' + x + 'px,' + y + 'px)');
            parent.css('-moz-transform', 'scale(' + scale + ') translate(' + x + 'px,' + y + 'px)');
            parent.css('-ms-transform', 'scale(' + scale + ') translate(' + x + 'px,' + y + 'px)');
            parent.css('-o-transform', 'scale(' + scale + ') translate(' + x + 'px,' + y + 'px)');
            parent.data('translateX', x);
            parent.data('translateY', y);
            $('body,html').animate({'scrollTop': 100}, 400);
        } else {
            resetZoom();
        }
    });
    function resetZoom() {
        if (parent.hasClass('zoomed')) {
            parent.removeClass('zoomed');
            parent.removeAttr('style');
            parent.removeAttr('data-translateX');
            parent.removeAttr('data-translateY');
        }
    }

    $(document).keyup(function (e) {
        if (e.keyCode == 27) {
            resetZoom();
        }
    });
    seat.on('dblclick', function (e) {
        if (parent.hasClass('zoomed')) {
            e.stopPropagation();
        }
    });
    seat.on('click', function () {
        if (parent.hasClass('zoomed') && !parent.hasClass('locked')) {
            var circle = $(this).find('circle'),
                text = $(this).find('text');
            if ($(this).attr('class') != 'active') {
                $(this).attr('class', 'active');
            } else {
                $(this).attr('class', '');
            }
        }
    });

    container.mousedown(function (evt) {
        if (parent.hasClass('zoomed') && !click) {
            evt.preventDefault();
            click = true;
            clickX = evt.clientX;
            clickY = evt.clientY;
        }
    });

    container.on('mouseup', function (evt) {
        if (parent.hasClass('zoomed') && click && mouseUpHandler) {
            evt.preventDefault();
            click = false;
            lastMoveX = moveX;
            lastMoveY = moveY;
            mouseUpHandler = false;
        }
    });

    container.mousemove(function (evt) {
        if (parent.hasClass('zoomed') && click && mouseMoveHandler) {
            evt.preventDefault();
            moveX = lastMoveX - clickX + evt.clientX;
            moveY = lastMoveY - clickY + evt.clientY;
            var newX = moveX * zoomRatio + parent.data('translateX'),
                newY = moveY * zoomRatio + parent.data('translateY');
            parent.css('transform', 'scale(' + scale + ') translate(' + newX + 'px,' + newY + 'px)');
            parent.css('-webkit-transform', 'scale(' + scale + ') translate(' + newX + 'px,' + newY + 'px)');
            parent.css('-moz-transform', 'scale(' + scale + ') translate(' + newX + 'px,' + newY + 'px)');
            parent.css('-ms-transform', 'scale(' + scale + ') translate(' + newX + 'px,' + newY + 'px)');
            parent.css('-o-transform', 'scale(' + scale + ') translate(' + newX + 'px,' + newY + 'px)');
            mouseMoveHandler = false;
        }
    });
    setInterval(function () {
        mouseMoveHandler = true;
        mouseUpHandler = true;
    }, 200);
});