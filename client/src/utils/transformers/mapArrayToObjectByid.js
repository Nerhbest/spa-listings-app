export const mapArrayToObjectById  =  (data) => {
    return data.reduce((acc,current) => {
       acc[current.id] = current;
       return acc;
    }, {});
};