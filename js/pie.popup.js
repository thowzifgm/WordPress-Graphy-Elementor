window.PiePopup = function ( separatedLabels,
                             point,
                             globalClass,
                             showLabel,
                             outsideChart,
                             labelOffset ){
    /** Convert HTML characters. */
    String.prototype.replaceAll = function(search, replacement) {
        const target = this;
        return target.split(search).join(replacement);
    };

    const Labels = separatedLabels;
    const Points = point;
    const StrLabels = Labels.replaceAll('&#039;', "").replaceAll('&#037;', "%");
    const str = StrLabels.split(', ');
    const arrayLabels = Array.from(str);
    const data = {
        labels: arrayLabels,
        series: Points
    };

    const options = {
        showLabel: showLabel,
        labelDirection: outsideChart === 'true' ? 'explode' : '',
        labelOffset: outsideChart === 'true' ? parseInt(labelOffset) : 0
    };

    setTimeout( () => {
        const pie = new Chartist.Pie( globalClass, data, options );
    }, 900 );

}