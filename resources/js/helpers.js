import moment from 'moment';

export const formatDateTime = string => {
    return moment(string).utc().format('ddd Do MMM "YY @ H:mma')
};
