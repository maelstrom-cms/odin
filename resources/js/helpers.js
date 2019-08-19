import moment from 'moment';

export const formatDateTime = string => {
    return moment(string).utc().format('ddd Do MMM "YY @ H:mma')
};

export const GREEN = '#72c040';
export const YELLOW = '#efaf41';
export const RED = '#e23c39';
