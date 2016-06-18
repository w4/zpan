import $ from 'jquery';

import 'material-design-lite';
import 'getmdl-select';
import './push-notifications';
import './ajax';

$('.a-submit a').click((event) => {
    $(event.currentTarget).parent('form').submit();
    return false;
});
